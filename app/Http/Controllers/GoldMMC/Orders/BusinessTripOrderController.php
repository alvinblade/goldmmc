<?php

namespace App\Http\Controllers\GoldMMC\Orders;

use App\Enums\AttendanceLogDayTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\BusinessTripOrder\BusinessTripOrderStoreRequest;
use App\Http\Requests\Orders\BusinessTripOrder\BusinessTripOrderUpdateRequest;
//use App\Models\Company\AttendanceLog;
//use App\Models\Company\AttendanceLogConfig;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Orders\BusinessTripOrder;
use Aws\Laravel\AwsFacade as AWS;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;

class BusinessTripOrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            return $this->error(message: "Şirkət tapılmadı", code: 404);
        }

        $businessTripOrders = BusinessTripOrder::query()
            ->where('company_id', $companyId)
            ->with('company')
            ->paginate($request->input('limit') ?? 10);

        return $this->success(data: new BusinessTripOrderCollection($businessTripOrders));
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function store(BusinessTripOrderStoreRequest $request)
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            return $this->error(message: "Şirkət tapılmadı", code: 404);
        }

        $data = $request->validated();
        $company = $this->getCompany($companyId);
        $employee = Employee::query()->with('position')
            ->where('company_id', $companyId)
            ->find($request->input('employee_id'));
        $companyName = $company->company_name;

        if (!$employee) {
            return $this->error(message: "İşçi tapılmadı", code: 404);
        }

        $orderNumber = generateOrderNumber(BusinessTripOrder::class, $company->company_short_name);
        $startDate = Carbon::parse($request->input('start_date'))->format('d.m.Y');
        $endDate = Carbon::parse($request->input('end_date'))->format('d.m.Y');
        $orderDate = Carbon::parse($request->input('order_date'))->format('d.m.Y');
        $char = substr($endDate, '-2');
        $lastChar = getNumberEnd($char);
        $gender = getGender($employee->gender);

        $data = array_merge($data, [
            'order_number' => $orderNumber,
            'name' => $employee->name,
            'surname' => $employee->surname,
            'father_name' => $employee->father_name,
            'd_name' => $company->director?->name,
            'd_surname' => $company->director?->surname,
            'd_father_name' => $company->director?->father_name,
            'tax_id_number' => $company->tax_id_number,
            'last_char' => $lastChar,
            'company_name' => $companyName,
            'gender' => $gender,
            'position' => $employee->position?->name,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'order_date' => $orderDate,
            'company_id' => $companyId
        ]);

        $startYear = Carbon::parse($request->input('start_date'))->format('Y');
        $startMonth = Carbon::parse($request->input('start_date'))->format('n');

        $endYear = Carbon::parse($request->input('end_date'))->format('Y');
        $endMonth = Carbon::parse($request->input('end_date'))->format('n');

        DB::beginTransaction();

        $existsAttendanceLog = AttendanceLog::query()
            ->where('company_id', $companyId)
            ->where('employee_id', $request->input('employee_id'))
            ->where('year', $startYear)
            ->where('month', $startMonth)
            ->first();

        if (!$existsAttendanceLog) {
            return $this->error(message: 'İşçi tabeldə mövcud deyil', code: 404);
        }

        $attendanceLogs = AttendanceLog::query()
            ->where('company_id', $companyId)
            ->where('employee_id', $request->input('employee_id'))
            ->whereBetween('year', [$startYear, $endYear])
            ->whereBetween('month', [$startMonth, $endMonth])
            ->get();

        foreach ($attendanceLogs as $log) {
            $monthDays = [];

            foreach ($log->days as $k => $day) {
                $dayDate = sprintf('%s-%02d-%02d', $log->year, $log->month, $day['day']);

                if ($dayDate >= $request->start_date && $dayDate <= $request->end_date) {
                    if ($day['status'] == AttendanceLogDayTypes::NULL_DAY->value) {
                        DB::rollBack();

                        return $this
                            ->error(message: "Ezamiyyət tarixi aralığı tabel üzrə düzgün qeyd olunmayıb",
                                code: 400);
                    }

                    $day['status'] = AttendanceLogDayTypes::BUSINESS_TRIP->value;

                    $monthDays[] = $day;
                } else {
                    $monthDays[] = $log->days[$k];
                }
            }

            $countMonthWorkDayHours = getMonthWorkDayHours($monthDays);
            $countCelebrationRestDays = getCelebrationRestDaysCount($monthDays);
            $countMonthWorkDays = getMonthWorkDaysCount($monthDays);

            $log->update([
                'salary' => $employee->salary,
                'days' => $monthDays,
                'month_work_days' => $countMonthWorkDays,
                'celebration_days' => $countCelebrationRestDays,
                'month_work_day_hours' => $countMonthWorkDayHours,
            ]);
        }

        $documentPath = public_path('assets/order_templates/BUSINESS_TRIP.docx');
        $fileName = 'BUSINESS_TRIP_ORDER_' . Str::slug($companyName . $orderNumber, '_') . '.docx';
        $filePath = public_path('assets/business_trip_orders/' . $fileName);

        $templateProcessor = new TemplateProcessor($documentPath);
        $this->templateProcessor($templateProcessor, $filePath, $data);

        $businessTripOrder = BusinessTripOrder::query()->create([
            'order_number' => $orderNumber,
            'company_id' => $companyId,
            'employee_id' => $request->input('employee_id'),
            'company_name' => $companyName,
            'tax_id_number' => $company->tax_id_number,
            'name' => $employee->name,
            'surname' => $employee->surname,
            'father_name' => $employee->father_name,
            'gender' => $employee->gender,
            'position' => $employee->position?->name,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'order_date' => $request->input('order_date'),
            'city_name' => $request->input('city_name'),
            'd_name' => $company->director?->name,
            'd_surname' => $company->director?->surname,
            'd_father_name' => $company->director?->father_name,
            'first_part_of_order' => $request->input('first_part_of_order'),
            'business_trip_to' => $request->input('business_trip_to'),
        ]);

        $generatedFilePath = returnOrderFile($filePath, $fileName, 'business_trip_orders');

        $businessTripOrder->update([
            'generated_file' => $generatedFilePath
        ]);

        unlink($filePath);

        DB::commit();

        return $this->success(data: $businessTripOrder, message: 'Ezamiyyət əmri uğurla yaradıldı');
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function update(BusinessTripOrderUpdateRequest $request, $businessTripOrder): JsonResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            return $this->error(message: 'Şirkət tapılmadı', code: 404);
        }

        $data = $request->validated();

        $businessTripOrder = BusinessTripOrder::query()
            ->where('company_id', $companyId)
            ->find($businessTripOrder);

        if (!$businessTripOrder) {
            return $this->error(message: 'Ezamiyyət əmri tapılmadı', code: 404);
        }

        $company = $this->getCompany($companyId);
        $employee = Employee::query()->with('position')
            ->where('company_id', $companyId)
            ->find($request->input('employee_id'));
        $companyName = $company->company_name;

        if (!$employee) {
            return $this->error(message: 'İşçi tapılmadı', code: 404);
        }

        $orderNumber = $businessTripOrder->order_number;
        $startDate = Carbon::parse($request->input('start_date'))->format('d.m.Y');
        $endDate = Carbon::parse($request->input('end_date'))->format('d.m.Y');
        $orderDate = Carbon::parse($request->input('order_date'))->format('d.m.Y');
        $char = substr($endDate, '-2');
        $lastChar = getNumberEnd($char);
        $gender = getGender($employee->gender);

        $data = array_merge($data, [
            'order_number' => $orderNumber,
            'name' => $employee->name,
            'surname' => $employee->surname,
            'father_name' => $employee->father_name,
            'd_name' => $company->director?->name,
            'd_surname' => $company->director?->surname,
            'd_father_name' => $company->director?->father_name,
            'position' => $employee->position?->name,
            'tax_id_number' => $company->tax_id_number,
            'last_char' => $lastChar,
            'company_name' => $companyName,
            'gender' => $gender,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'order_date' => $orderDate,
            'company_id' => $companyId
        ]);

        $documentPath = public_path('assets/order_templates/BUSINESS_TRIP.docx');
        $fileName = 'BUSINESS_TRIP_ORDER_' . Str::slug($companyName . $orderNumber, '_') . '.docx';
        $filePath = public_path('assets/business_trip_orders/' . $fileName);
        $templateProcessor = new TemplateProcessor($documentPath);
        $this->templateProcessor($templateProcessor, $filePath, $data);

        $businessTripOrderCurrentFile = $businessTripOrder->generated_file ?? [];

        $s3 = AWS::createClient('s3');
        $s3->deleteObject(array(
            'Bucket' => $businessTripOrderCurrentFile[0]['bucket'],
            'Key' => $businessTripOrderCurrentFile[0]['generated_name']
        ));
        $generatedFilePath = returnOrderFile($filePath, $fileName, 'business_trip_orders');

        $businessTripOrder->update([
            'company_id' => $companyId,
            'employee_id' => $request->input('employee_id'),
            'company_name' => $companyName,
            'tax_id_number' => $company->tax_id_number,
            'name' => $employee->name,
            'surname' => $employee->surname,
            'father_name' => $employee->father_name,
            'position' => $employee->position?->name,
            'gender' => $employee->gender,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'order_date' => $request->input('order_date'),
            'city_name' => $request->input('city_name'),
            'd_name' => $company->director?->name,
            'd_surname' => $company->director?->surname,
            'd_father_name' => $company->director?->father_name,
            'first_part_of_order' => $request->input('first_part_of_order'),
            'business_trip_to' => $request->input('business_trip_to'),
            'generated_file' => $generatedFilePath
        ]);

        unlink($filePath);

        return $this->success(data: $businessTripOrder, message: 'Ezamiyyət əmri uğurla yeniləndi');
    }

    public function show($businessTripOrder): JsonResponse
    {
        $companyId = getHeaderCompanyId();

        $businessTripOrder = BusinessTripOrder::query()
            ->where('company_id', $companyId)
            ->with('company')->find($businessTripOrder);

        if (!$businessTripOrder) {
            return $this->error(message: 'Ezamiyyət əmri tapılmadı', code: 404);
        }

        return $this->success(data: BusinessTripOrderResource::make($businessTripOrder));
    }

    private function getCompany($companyId): Builder|array|Collection|Model
    {
        return Company::query()->with(['mainUser', 'director'])->find($companyId);
    }

    private function templateProcessor(TemplateProcessor $templateProcessor, $filePath, $data): void
    {
        $templateProcessor->setValue('order_number', $data['order_number']);
        $templateProcessor->setValue('company_name', $data['company_name']);
        $templateProcessor->setValue('company_tax_id_number', $data['tax_id_number']);
        $templateProcessor->setValue('name', $data['name']);
        $templateProcessor->setValue('surname', $data['surname']);
        $templateProcessor->setValue('father_name', $data['father_name']);
        $templateProcessor->setValue('gender', $data['gender']);
        $templateProcessor->setValue('start_date', $data['start_date']);
        $templateProcessor->setValue('end_date', $data['end_date'] . $data['last_char']);
        $templateProcessor->setValue('order_date', $data['order_date']);
        $templateProcessor->setValue('city_name', $data['city_name']);
        $templateProcessor->setValue('d_name', $data['d_name']);
        $templateProcessor->setValue('d_surname', $data['d_surname']);
        $templateProcessor->setValue('d_father_name', $data['d_father_name']);
        $templateProcessor->setValue('first_part_of_order', $data['first_part_of_order']);
        $templateProcessor->setValue('business_trip_to', $data['business_trip_to']);
        $templateProcessor->setValue('position', $data['position']);
        $templateProcessor->saveAs($filePath);
    }

    public function destroy($businessTripOrder): JsonResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            return $this->error(message: 'Şirkət tapılmadı', code: 404);
        }

        $businessTripOrder = BusinessTripOrder::query()
            ->where('company_id', $companyId)
            ->find($businessTripOrder);

        if (!$businessTripOrder) {
            return $this->error(message: 'Ezamiyyət əmri tapılmadı', code: 404);
        }

        $businessTripOrderCurrentFile = $businessTripOrder->generated_file ?? [];
        $s3 = AWS::createClient('s3');

        $getObject = $s3->listObjects([
            'Bucket' => $businessTripOrderCurrentFile[0]['bucket'],
            'Key' => $businessTripOrderCurrentFile[0]['generated_name']
        ]);

        if (is_array($getObject['Contents']) && count($getObject['Contents']) > 0) {
            $s3->deleteObject(array(
                'Bucket' => $businessTripOrderCurrentFile[0]['bucket'],
                'Key' => $businessTripOrderCurrentFile[0]['generated_name']
            ));
        }

        $businessTripOrder->delete();

        return $this->success(message: 'Ezamiyyət əmri uğurla silindi');
    }
}
