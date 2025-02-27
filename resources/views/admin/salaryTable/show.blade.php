@extends('admin.index')
@section('title')
    Əmək haqqı cədvəli - {{ $year }}
@endsection
@section('content')
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Əmək haqqı cədvəli - {{ $year }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content w-100">
            @foreach($attendanceLogs->groupBy('month') as $month => $logs)
                @php
                    $monthName = \Carbon\Carbon::create($year, $month)->translatedFormat('F');
                    $daysCount = \Carbon\Carbon::create($year, $month)->daysInMonth;
                @endphp
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">ƏMƏK HAQQI CƏDVƏLİ {{ $year }} - {{ $monthName }}</h3>
                        <div class="block-options">
                            <div class="block-options-item">
                                <div class="d-flex align-items-center justify-content-center">
                                    <a href="{{ route('admin.salaryEmployees.excel.export', ['year' => $year, 'month' => $month]) }}"
                                       class="btn btn-primary">
                                        Excel export <i class="fas fa-file-excel"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="table-responsive">
                            <table class="table table-sm table-vcenter">
                                <thead>
                                <tr>
                                    <th class="fs-sm">№</th>
                                    <th class="fs-sm">İşçi</th>
                                    <th class="fs-sm">Vəzifəsi</th>
                                    <th class="fs-sm">Maaş</th>
                                    <th class="fs-sm">Ayda İ.S</th>
                                    <th class="fs-sm">Faktiki İ.S</th>
                                    <th class="fs-sm">Hesablanmış Ə.H</th>
                                    <th class="fs-sm">Mükafat</th>
                                    <th class="fs-sm">Məzuniyyət</th>
                                    <th class="fs-sm">CƏMİ</th>
                                    <th class="fs-sm">G.V (14%)</th>
                                    <th class="fs-sm">P.F (10%)</th>
                                    <th class="fs-sm">İ.S.H (0.5%)</th>
                                    <th class="fs-sm">İ.T.S.H (2%)</th>
                                    <th class="fs-sm">Tutulmuşdur</th>
                                    <th class="fs-sm">Ödəniləcək</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $totalMonthWorkDays = 0;
                                    $totalCelebrationDays = 0;
                                    $totalMonthWorkDayHours = 0;
                                @endphp
                                @foreach($logs as $key => $attendanceLog)
                                    @php
                                        $holidaysCount = 0;

                                        foreach ($attendanceLog->days as $day){
                                            if ($day['status'] == \App\Enums\AttendanceLogDayTypes::DEFAULT_HOLIDAY->value) {
                                                $holidaysCount+=1;
                                            }
                                        }

                                        (float)$awardedSalary = 0;
                                        (float)$calculatedSalary = $attendanceLog->employee?->salary / $attendanceLog->month_work_hours * $attendanceLog->month_work_day_hours;
                                        $holidaySalary = \App\Models\Company\AttendanceLog::query()->with('employee')->where('employee_id', $attendanceLog->employee_id)->sum('salary')/12/30.4*$holidaysCount;
                                        $totalSalary = $awardedSalary + $calculatedSalary + $holidaySalary;

                                        (float)$gTax = 0;
                                        (float)$pFund = 0;
                                        (float)$iSH = 0;
                                        (float)$iTSH = 0;

                                        if($totalSalary > 8000){
                                            $gTax = ($totalSalary - 8000)*0.14;
                                        }

                                        if($totalSalary <= 8000){
                                            (float)$gTax = 0;
                                        }

                                        if($totalSalary <= 200){
                                            $pFund = $totalSalary * 0.03;
                                        }

                                        if($totalSalary > 200){
                                            $pFund = 6 + ($totalSalary - 200) * 0.1;
                                        }

                                        $iSH = $totalSalary * 0.005;

                                        if($totalSalary <= 8000){
                                            $iTSH =  $totalSalary * 0.02;
                                        }

                                        if($totalSalary > 8000){
                                            $iTSH =  160 + ($totalSalary - 8000) * 0.005;
                                        }
                                    @endphp
                                    <tr>
                                        <td class="fs-sm">
                                            {{ $key + 1 }}
                                        </td>
                                        <td class="fs-sm">
                                        <span>
                                            {{ $attendanceLog->employee?->name . ' ' . $attendanceLog->employee?->surname }}
                                        </span>
                                        </td>
                                        <td class="fs-sm">
                                        <span class="badge bg-primary">
                                            {{ $attendanceLog->employee?->position?->name }}
                                        </span>
                                        </td>
                                        <td class="fs-sm">{{ $attendanceLog->employee?->salary }}</td>
                                        <td class="fs-sm">
                                            {{ $attendanceLog->month_work_hours }}
                                        </td>
                                        <td class="fs-sm">
                                            {{ $attendanceLog->month_work_day_hours }}
                                        </td>
                                        <td class="fs-sm">
                                            {!! $calculatedSalary !!}
                                        </td>
                                        <td class="fs-sm">
                                            {{ $awardedSalary }}
                                        </td>
                                        <td class="fs-sm">
                                            {!! $holidaySalary !!}
                                        </td>
                                        <td class="fs-sm">
                                            {!! $totalSalary  !!}
                                        </td>
                                        <td class="fs-sm">
                                            {!! $gTax !!}
                                        </td>
                                        <td class="fs-sm">
                                            {!! $pFund !!}
                                        </td>
                                        <td class="fs-sm">
                                            {!! $iSH !!}
                                        </td>
                                        <td class="fs-sm">
                                            {!! $iTSH !!}
                                        </td>
                                        <td class="fs-sm">
                                            {!! $gTax + $pFund + $iSH + $iTSH !!}
                                        </td>
                                        <td class="fs-sm">
                                            {!! $totalSalary - ($gTax + $pFund + $iSH + $iTSH) !!}
                                        </td>
                                    </tr>
                                    @php
                                        $totalMonthWorkDays += $attendanceLog->month_work_days;
                                        $totalCelebrationDays += $attendanceLog->celebration_days;
                                        $totalMonthWorkDayHours += $attendanceLog->month_work_day_hours;
                                    @endphp
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </main>
@endsection
