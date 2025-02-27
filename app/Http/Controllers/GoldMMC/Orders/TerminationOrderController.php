<?php

namespace App\Http\Controllers\GoldMMC\Orders;

use App\Enums\AttendanceLogDayTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\TerminationOrder\TerminationOrderStoreRequest;
use App\Models\Company\AttendanceLog;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Orders\TerminationOrder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use DB;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Contracts\View\View;

class TerminationOrderController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $terminationOrders = TerminationOrder::query()
            ->where('company_id', $companyId)
            ->with('company')
            ->when(request()->filled('search'), function ($query) {
                return $query->where('order_number', 'like', '%' . request('search') . '%');
            })
            ->paginate(10);

        return view('admin.terminationOrders.index', compact('terminationOrders'));
    }

    public function create(): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $employees = Employee::query()
            ->with('position')
            ->where('company_id', $companyId)
            ->get();

        return view('admin.terminationOrders.create', compact('employees'));
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function store(TerminationOrderStoreRequest $request): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $data = $request->validated();
        $company = $this->getCompany($companyId);
        $companyName = $company->company_name;
        $employee = Employee::query()
            ->where('company_id', $companyId)
            ->with('position')->find($request->input('employee_id'));

        if (!$employee) {
            toast('İşçi tapılmadı', 'error');
            return redirect()->back();
        }

        $terminationDate = Carbon::parse($request->input('termination_date'))->format('d.m.Y');
        $employmentStartDate = Carbon::parse($request->input('employment_start_date'))->format('d.m.Y');

        $orderNumber = generateOrderNumber(TerminationOrder::class, $company->company_short_name);

        DB::beginTransaction();

        $existsAttendanceLog = AttendanceLog::query()
            ->where('company_id', $companyId)
            ->where('employee_id', $request->input('employee_id'))
            ->first();

        if (!$existsAttendanceLog) {
            toast('İşçi tabeldə mövcud deyil', 'error');
            return redirect()->back();
        }

        $attendanceLogs = AttendanceLog::query()
            ->where('company_id', $companyId)
            ->where('employee_id', $request->input('employee_id'))
            ->get();

        $jsonDataOfAttendanceLogs = collect($attendanceLogs->toArray())->toJson();

        foreach ($attendanceLogs as $log) {
            $monthDays = [];

            foreach ($log->days as $k => $day) {
                $dayDate = sprintf('%s-%02d-%02d', $log->year, $log->month, $day['day']);

                if ($dayDate >= $request->termination_date) {
                    if ($day['status'] == AttendanceLogDayTypes::NULL_DAY->value) {
                        DB::rollBack();

                        toast('Xitam tarixi aralığı tabel üzrə düzgün qeyd olunmayıb', 'error');
                        return redirect()->back();
                    }

                    $day['status'] = AttendanceLogDayTypes::LEAVING_WORK->value;

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

        $char1 = substr($terminationDate, '-2');
        $char2 = substr($employmentStartDate, '-2');
        $lastChar1 = getNumberEnd($char1);
        $lastChar2 = getNumberEnd($char2);
        $gender = getGender($employee->gender);

        $data = array_merge($data, [
            'order_number' => $orderNumber,
            'name' => $employee->name,
            'surname' => $employee->surname,
            'father_name' => $employee->father_name,
            'last_char1' => $lastChar1,
            'last_char2' => $lastChar2,
            'company_name' => $companyName,
            'gender' => $gender,
            'termination_date' => $terminationDate,
            'employment_start_date' => $employmentStartDate,
            'tax_id_number' => $company->tax_id_number,
            'd_name' => $company->director?->name,
            'd_surname' => $company->director?->surname,
            'd_father_name' => $company->director?->father_name,
        ]);

        $documentPath = public_path('assets/order_templates/LEAVING_WORK.docx');
        $fileName = 'TERMINATION_ORDER_' . Str::slug($companyName . $orderNumber, '_') . '.docx';
        $filePath = public_path('assets/termination_orders/' . $fileName);

        $templateProcessor = new TemplateProcessor($documentPath);
        $this->templateProcessor($templateProcessor, $filePath, $data);

        $terminationOrder = TerminationOrder::query()->create([
            'order_number' => $orderNumber,
            'company_id' => $companyId,
            'employee_id' => $request->input('employee_id'),
            'company_name' => $companyName,
            'tax_id_number' => $company->tax_id_number,
            'name' => $employee->name,
            'surname' => $employee->surname,
            'father_name' => $employee->father_name,
            'days_count' => $request->input('days_count'),
            'gender' => $employee->gender,
            'termination_date' => $request->input('termination_date'),
            'employment_start_date' => $request->input('employment_start_date'),
            'd_name' => $company->director?->name,
            'd_surname' => $company->director?->surname,
            'd_father_name' => $company->director?->father_name,
            'main_part_of_order' => $request->input('main_part_of_order'),
            'backup_of_logs' => $jsonDataOfAttendanceLogs
        ]);

        $generatedFilePath = returnOrderFile('assets/termination_orders/' . $fileName,
            $fileName, 'termination_orders');

        $terminationOrder->update([
            'generated_file' => $generatedFilePath
        ]);

        DB::commit();

        toast('Xitam əmri uğurla yaradıldı', 'success');

        return redirect()->route('admin.terminationOrders.index');
    }

    public function show($terminationOrder): JsonResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            return $this->error(message: "Şirkət tapılmadı", code: 404);
        }

        $terminationOrder = TerminationOrder::query()
            ->where('company_id', $companyId)
            ->with('company')->find($terminationOrder);

        if (!$terminationOrder) {
            return $this->error(message: 'Xitam əmri tapılmadı', code: 404);
        }

        return $this->success(data: TerminationOrderResource::make($terminationOrder));
    }

    private function getCompany($companyId): Builder|array|Collection|Model
    {
        return Company::query()->with(['mainEmployee', 'director'])->find($companyId);
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
        $templateProcessor->setValue('employment_start_date', $data['employment_start_date'] . $data['last_char2']);
        $templateProcessor->setValue('termination_date', $data['termination_date'] . $data['last_char1']);
        $templateProcessor->setValue('days_count', $data['days_count']);
        $templateProcessor->setValue('d_name', $data['d_name']);
        $templateProcessor->setValue('d_surname', $data['d_surname']);
        $templateProcessor->setValue('d_father_name', $data['d_father_name']);
        $templateProcessor->setValue('main_part_of_order', $data['main_part_of_order']);
        $templateProcessor->saveAs($filePath);
    }

    public function destroy($terminationOrder): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $terminationOrder = TerminationOrder::query()
            ->where('company_id', $companyId)
            ->find($terminationOrder);

        if (!$terminationOrder) {
            toast('Xitam əmri tapılmadı', 'error');
            return redirect()->back();
        }

        foreach (json_decode($terminationOrder->backup_of_logs, true) as $log) {
            $dbLog = AttendanceLog::query()
                ->where(function ($query) use ($log) {
                    $query
                        ->where('id', $log['id'])
                        ->where('employee_id', $log['employee_id']);
                })
                ->first();

            $dbLog?->update([
                'days' => $log['days']
            ]);
        }

        if (!empty($terminationOrder->generated_file[0]['path'])) {
            $filePath = public_path($terminationOrder->generated_file[0]['path']);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $terminationOrder->delete();

        toast('Xitam əmri uğurla silindi', 'success');
        return redirect()->route('admin.terminationOrders.index');
    }
}
