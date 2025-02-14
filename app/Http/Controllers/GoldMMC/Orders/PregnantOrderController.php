<?php

namespace App\Http\Controllers\GoldMMC\Orders;

use App\Enums\AttendanceLogDayTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\PregnantOrder\PregnantOrderStoreRequest;
use App\Models\Company\AttendanceLog;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Orders\PregnantOrder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use DB;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Contracts\View\View;

class PregnantOrderController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $pregnantOrders = PregnantOrder::query()
            ->where('company_id', $companyId)
            ->with('company')
            ->when(request()->filled('search'), function ($query) {
                return $query->where('order_number', 'like', '%' . request('search') . '%');
            })
            ->paginate(10);

        return view('admin.pregnantOrders.index', compact('pregnantOrders'));
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

        return view('admin.pregnantOrders.create', compact('employees'));
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function store(PregnantOrderStoreRequest $request): RedirectResponse
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
            toast('İşçi tapılmadı', 'error');
            return redirect()->back();
        }

        $orderNumber = generateOrderNumber(PregnantOrder::class, $company->company_short_name);
        $holidayStartDate = Carbon::parse($request->input('holiday_start_date'))->format('d.m.Y');
        $holidayEndDate = Carbon::parse($request->input('holiday_end_date'))->format('d.m.Y');
        $employmentStartDate = Carbon::parse($request->input('employment_start_date'))->format('d.m.Y');

        $startYear = Carbon::parse($request->input('holiday_start_date'))->format('Y');
        $startMonth = Carbon::parse($request->input('holiday_start_date'))->format('n');

        $endYear = Carbon::parse($request->input('holiday_end_date'))->format('Y');
        $endMonth = Carbon::parse($request->input('holiday_end_date'))->format('n');

        DB::beginTransaction();

        $existsAttendanceLog = AttendanceLog::query()
            ->where('company_id', $companyId)
            ->where('employee_id', $request->input('employee_id'))
            ->where('year', $startYear)
            ->where('month', $startMonth)
            ->first();

        if (!$existsAttendanceLog) {
            toast('İşçi tabeldə mövcud deyil', 'error');
            return redirect()->back();
        }

        $attendanceLogs = AttendanceLog::query()
            ->where('company_id', $companyId)
            ->where('employee_id', $request->input('employee_id'))
            ->whereBetween('year', [$startYear, $endYear])
            ->whereBetween('month', [$startMonth, $endMonth])
            ->get();

        $jsonDataOfAttendanceLogs = collect($attendanceLogs->toArray())->toJson();

        foreach ($attendanceLogs as $log) {
            $monthDays = [];

            foreach ($log->days as $k => $day) {
                $dayDate = sprintf('%s-%02d-%02d', $log->year, $log->month, $day['day']);

                if ($dayDate >= $request->holiday_start_date && $dayDate <= $request->holiday_end_date) {
                    if ($day['status'] == AttendanceLogDayTypes::NULL_DAY->value) {
                        DB::rollBack();

                        toast('Hamiləlik məzuniyyəti tarixi aralığı tabel üzrə düzgün qeyd olunmayıb', 'error');

                        return redirect()->back();
                    }

                    $day['status'] = AttendanceLogDayTypes::DEFAULT_HOLIDAY->value;

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

        $gender = getGender($employee->gender);

        $data = array_merge($data, [
            'order_number' => $orderNumber,
            'name' => $employee->name,
            'surname' => $employee->surname,
            'father_name' => $employee->father_name,
            'position' => $employee->position?->name,
            'company_name' => $companyName,
            'gender' => $gender,
            'holiday_start_date' => $holidayStartDate,
            'holiday_end_date' => $holidayEndDate,
            'employment_start_date' => $employmentStartDate,
            'tax_id_number' => $company->tax_id_number,
            'd_name' => $company->director?->name,
            'd_surname' => $company->director?->surname,
            'd_father_name' => $company->director?->father_name,
            'company_id' => $companyId,
        ]);

        $documentPath = public_path('assets/order_templates/PREGNANT_HOLIDAY.docx');
        $fileName = 'PREGNANT_ORDER_' . Str::slug($companyName . $orderNumber, '_') . '.docx';
        $filePath = public_path('assets/pregnant_orders/' . $fileName);

        $templateProcessor = new TemplateProcessor($documentPath);
        $this->templateProcessor($templateProcessor, $filePath, $data);

        $pregnantOrder = PregnantOrder::query()->create([
            'order_number' => $orderNumber,
            'company_id' => $companyId,
            'employee_id' => $request->input('employee_id'),
            'company_name' => $companyName,
            'tax_id_number' => $company->tax_id_number,
            'name' => $employee->name,
            'position' => $employee->position?->name,
            'surname' => $employee->surname,
            'father_name' => $employee->father_name,
            'gender' => $employee->gender,
            'type_of_holiday' => $request->input('type_of_holiday'),
            'holiday_start_date' => $request->input('holiday_start_date'),
            'holiday_end_date' => $request->input('holiday_end_date'),
            'employment_start_date' => $request->input('employment_start_date'),
            'd_name' => $company->director?->name,
            'd_surname' => $company->director?->surname,
            'd_father_name' => $company->director?->father_name,
            'main_part_of_order' => $request->input('main_part_of_order'),
            'backup_of_logs' => $jsonDataOfAttendanceLogs,
        ]);

        $generatedFilePath = returnOrderFile('assets/pregnant_orders/' . $fileName,
            $fileName, 'pregnant_orders');

        $pregnantOrder->update([
            'generated_file' => $generatedFilePath
        ]);

        DB::commit();

        toast('Hamiləlik məzuniyyəti əmri uğurla yaradıldı', 'success');

        return redirect()->route('admin.pregnantOrders.index');
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
        $templateProcessor->setValue('position', $data['position']);
        $templateProcessor->setValue('employment_start_date', $data['employment_start_date']);
        $templateProcessor->setValue('holiday_start_date', $data['holiday_start_date']);
        $templateProcessor->setValue('holiday_end_date', $data['holiday_end_date']);
        $templateProcessor->setValue('type_of_holiday', $data['type_of_holiday']);
        $templateProcessor->setValue('d_name', $data['d_name']);
        $templateProcessor->setValue('d_surname', $data['d_surname']);
        $templateProcessor->setValue('d_father_name', $data['d_father_name']);
        $templateProcessor->setValue('main_part_of_order', $data['main_part_of_order']);
        $templateProcessor->saveAs($filePath);
    }

    public function destroy($pregnantOrder): RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $pregnantOrder = PregnantOrder::query()
            ->where('company_id', $companyId)
            ->find($pregnantOrder);

        if (!$pregnantOrder) {
            toast('Hamiləlik məzuniyyəti əmri tapılmadı', 'error');
            return redirect()->back();
        }

        foreach (json_decode($pregnantOrder->backup_of_logs, true) as $log) {
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

        if (!empty($pregnantOrder->generated_file[0]['path'])) {
            $filePath = public_path($pregnantOrder->generated_file[0]['path']);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $pregnantOrder->delete();

        toast('Hamiləlik məzuniyyəti əmri uğurla silindi', 'success');

        return redirect()->route('admin.pregnantOrders.index');
    }
}
