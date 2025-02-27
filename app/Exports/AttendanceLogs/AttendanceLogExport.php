<?php

namespace App\Exports\AttendanceLogs;

use App\Models\Company\AttendanceLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Concerns\FromView;

class AttendanceLogExport implements FromView
{
    private mixed $req;

    public function __construct($req)
    {
        $this->req = $req;
    }

    public function view(): View
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
        }

        $attendanceLogs = AttendanceLog::query()
            ->with(['employee', 'employee.position', 'company', 'company.director'])
            ->where('company_id', '=', $companyId)
            ->where('year', '=', $this->req['year'])
            ->where('month', '=', $this->req['month'])
            ->get();

        return view('exports.attendance_logs.attendance_log_export', [
            'attendanceLogs' => $attendanceLogs
        ]);
    }
}
