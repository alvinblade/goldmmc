<?php

namespace App\Http\Controllers\GoldMMC\Employees;

use App\Http\Controllers\Controller;
use App\Models\Company\AttendanceLog;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SalaryController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast("Şirkət tapılmadı", 'error');
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

        return view('admin.salaryTable.index', compact('attendanceLogs'));
    }

    public function show($year): View|RedirectResponse
    {
        $companyId = getHeaderCompanyId();

        if (!$companyId) {
            toast('Şirkət tapılmadı', 'error');
            return redirect()->back();
        }

        $attendanceLogs = AttendanceLog::query()
            ->with(['employee', 'employee.position', 'company', 'company.director'])
            ->where(
                function ($query) use ($companyId, $year) {
                    $query->where('company_id', $companyId)
                        ->where('year', $year);
                }
            )
            ->orderBy('month', 'asc')
            ->get();

        if (empty($attendanceLogs)) {
            toast('Tabel məlumatları tapılmadı', 'error');
            return redirect()->back();
        }

        return view('admin.salaryTable.show', compact('attendanceLogs', 'year'));
    }
}
