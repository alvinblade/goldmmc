<?php

namespace App\Http\Controllers\GoldMMC\Excel;

use App\Exports\AttendanceLogs\AttendanceLogExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AttendanceLogExcelController extends Controller
{
    public function exportAttendanceLogExcel(Request $request): BinaryFileResponse
    {
        $req = $request->validate([
            'year' => ['required', 'integer'],
            'month' => ['required', 'integer']
        ]);

        return Excel::download(new AttendanceLogExport($req), 'attendance_log_export.xlsx');
    }
}
