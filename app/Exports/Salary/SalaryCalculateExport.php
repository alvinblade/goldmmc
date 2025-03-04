<?php

namespace App\Exports\Salary;

use App\Models\Company\AttendanceLog;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalaryCalculateExport implements FromView
{
    private mixed $req;

    public function __construct($req)
    {
        $this->req = $req;
    }

    public function view(): View
    {
        $companyId = getHeaderCompanyId();

        $attendanceLogs = AttendanceLog::query()
            ->with(['employee', 'employee.position', 'company', 'company.director'])
            ->where('company_id', '=', $companyId)
            ->where('year', '=', $this->req['year'])
            ->where('month', '=', $this->req['month'])
            ->get();

        return view('exports.salary_calculate.salary_calculate_export', [
            'attendanceLogs' => $attendanceLogs
        ]);
    }
}
