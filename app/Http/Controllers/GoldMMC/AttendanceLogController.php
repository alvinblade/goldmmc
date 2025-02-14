<?php

namespace App\Http\Controllers\GoldMMC;

use App\Http\Controllers\Controller;
use App\Models\Company\AttendanceLog;
use App\Models\Company\AttendanceLogConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttendanceLogController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $attendanceLogs = AttendanceLog::query()
            ->where('company_id', $companyId)
            ->with([
                'company:id,company_name',
                'employee:id,name,surname,position_id',
                'employee.position:id,name'
            ])
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy('year');

        return view('admin.attendanceLogs.index', compact('attendanceLogs'));
    }

    public function show($year): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $attendanceLogs = AttendanceLog::query()
            ->where(
                function ($query) use ($companyId, $year) {
                    $query->where('company_id', $companyId)
                        ->where('year', $year);
                }
            )
            ->with([
                'company:id,company_name',
                'employee:id,name,surname,position_id',
                'employee.position:id,name'
            ])->orderBy('month', 'asc')->get();

        if (empty($attendanceLogs)) {
            toast('Tabel məlumatları tapılmadı', 'error');
            return redirect()->back();
        }

        return view('admin.attendanceLogs.show', compact('attendanceLogs', 'year'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'attendance_log_config_id' => ['required', 'integer', Rule::exists('attendance_log_configs', 'id')
                ->where(function ($query) use ($request) {
                    $query->where('company_id', $request->input('company_id'));
                })],
            'employee_id' => ['required', 'integer', Rule::exists('employees', 'id')
                ->where(function ($query) use ($request) {
                    $query->where('company_id', $request->input('company_id'));
                })],
        ]);

        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            return $this->error(message: "Şirkət tapılmadı", code: 404);
        }

        $attendaceLogConfig = AttendanceLogConfig::query()
            ->where('company_id', $companyId)
            ->find($request->input('attendance_log_config_id'));

        $request->validate([
            'employee_id' => [Rule::unique('attendance_logs', 'employee_id')
                ->where(function ($query) use ($attendaceLogConfig) {
                    $query->where('company_id', $attendaceLogConfig->company_id)
                        ->where('year', $attendaceLogConfig->year)
                        ->where('month', $attendaceLogConfig->month);
                })]
        ]);

        $countMonthWorkDayHours = getMonthWorkDayHours($attendaceLogConfig->config);
        $countCelebrationRestDays = getCelebrationRestDaysCount($attendaceLogConfig->config);
        $countMonthWorkDays = getMonthWorkDaysCount($attendaceLogConfig->config);

        $attendanceLog = new AttendanceLog();
        $attendanceLog->company_id = $attendaceLogConfig->company_id;
        $attendanceLog->employee_id = $request->input('employee_id');
        $attendanceLog->year = $attendaceLogConfig->year;
        $attendanceLog->month = $attendaceLogConfig->month;
        $attendanceLog->days = $attendaceLogConfig->config;
        $attendanceLog->month_work_days = $countMonthWorkDays;
        $attendanceLog->celebration_days = $countCelebrationRestDays;
        $attendanceLog->month_work_day_hours = $countMonthWorkDayHours;
        $attendanceLog->save();

        return $this->success(data: new AttendanceLogResource($attendanceLog),
            message: "İşçi tabelə uğurla əlavə olundu", code: 201);
    }

    public function update(Request $request, $attendanceLog): JsonResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            return $this->error(message: "Şirkət tapılmadı", code: 404);
        }

        $request->validate([
            'attendance_log_config_id' => ['required', 'integer', Rule::exists('attendance_log_configs', 'id')
                ->where(function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })],
            'employee_id' => ['required', 'integer', Rule::exists('employees', 'id')
                ->where(function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                })],
        ]);

        $attendaceLogConfig = AttendanceLogConfig::query()
            ->where('company_id', $companyId)
            ->find($request->input('attendance_log_config_id'));

        $attendanceLog = AttendanceLog::query()
            ->where('company_id', $companyId)
            ->find($attendanceLog);

        if (!$attendanceLog) {
            return $this->error(message: "Tabel məlumatı tapılmadı", code: 404);
        }

        $request->validate([
            'employee_id' => [Rule::unique('attendance_logs', 'employee_id')
                ->where(function ($query) use ($attendaceLogConfig) {
                    $query->where('company_id', $attendaceLogConfig->company_id)
                        ->where('year', $attendaceLogConfig->year)
                        ->where('month', $attendaceLogConfig->month);
                })->ignore($attendanceLog->id)]
        ]);

        $countMonthWorkDayHours = getMonthWorkDayHours($attendaceLogConfig->config);
        $countCelebrationRestDays = getCelebrationRestDaysCount($attendaceLogConfig->config);
        $countMonthWorkDays = getMonthWorkDaysCount($attendaceLogConfig->config);

        $attendanceLog = new AttendanceLog();
        $attendanceLog->company_id = $attendaceLogConfig->company_id;
        $attendanceLog->employee_id = $request->input('employee_id');
        $attendanceLog->year = $attendaceLogConfig->year;
        $attendanceLog->month = $attendaceLogConfig->month;
        $attendanceLog->days = $attendaceLogConfig->config;
        $attendanceLog->month_work_days = $countMonthWorkDays;
        $attendanceLog->celebration_days = $countCelebrationRestDays;
        $attendanceLog->month_work_day_hours = $countMonthWorkDayHours;
        $attendanceLog->save();

        return $this->success(data: new AttendanceLogResource($attendanceLog),
            message: "İşçi tabelə uğurla əlavə olundu", code: 201);
    }

    public function destroy($attendanceLog): JsonResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            return $this->error(message: "Şirkət tapılmadı", code: 404);
        }

        $attendanceLog = AttendanceLog::query()
            ->where('company_id', $companyId)
            ->find($attendanceLog);

        if (!$attendanceLog) {
            return $this->error(message: "Tabel məlumatı tapılmadı", code: 404);
        }

        $attendanceLog->delete();

        return $this->success(message: "Tabel məlumatı ugurla silindi");
    }
}
