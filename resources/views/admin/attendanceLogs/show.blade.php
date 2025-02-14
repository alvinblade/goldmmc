@extends('admin.index')
@section('title')
    Tabel cədvəli - {{ $year }}
@endsection
@section('content')
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Tabel cədvəli - {{ $year }}
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
                        <h3 class="block-title">TABEL CƏDVƏLİ {{ $year }} - {{ $monthName }}</h3>
                    </div>
                    <div class="block-content">
                        <table class="js-table-sections table table-sm table-vcenter">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">№</th>
                                <th>Ad</th>
                                @foreach($logs->first()->days as $dayOfLog)
                                    <th>
                                        {{ $dayOfLog['day'] }}
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody class="js-table-sections-header">
                            @foreach ($logs as $index => $log)
                                <tr>
                                    <th class="text-center" scope="row">{{$index+=1}}</th>
                                    <td class="fs-sm">
                                        {{ $log->employee?->name }} {{ $log->employee?->surname }}
                                    </td>
                                    @foreach($log->days as $day)
                                        @switch($day['status'])
                                            @case('NULL_DAY')
                                                <td class="text-center fw-bold text-muted">
                                                    -
                                                </td>
                                                @break
                                            @case('REST_DAY')
                                                <td class="text-center fw-bold text-warning">
                                                    İ
                                                </td>
                                                @break
                                            @case('DAY_OF_CELEBRATION')
                                                <td class="text-center fw-bold text-primary">
                                                    B
                                                </td>
                                                @break
                                            @case('LEAVING_WORK')
                                                <td class="text-center fw-bold text-danger">
                                                    İ/Ç
                                                </td>
                                                @break
                                            @case('ILLNESS')
                                                <td class="text-center fw-bold text-danger">
                                                    X
                                                </td>
                                                @break
                                            @case('BUSINESS_TRIP')
                                                <td class="text-center fw-bold text-success">
                                                    E
                                                </td>
                                                @break
                                            @case('DEFAULT_HOLIDAY')
                                                <td class="text-center fw-bold text-info">
                                                    M
                                                </td>
                                                @break
                                            @default
                                                <td class="text-center fw-bold">
                                                    {{ $day['status'] }}
                                                </td>
                                                @break
                                        @endswitch
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </main>
@endsection
