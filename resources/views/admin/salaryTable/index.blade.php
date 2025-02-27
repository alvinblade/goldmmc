@extends('admin.index')

@section('title')
    Əmək haqqı cədvəlləri
@endsection
@section('content')
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Əmək haqqı cədvəlləri
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content w-100">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">ƏMƏK HAQQI CƏDVƏLLƏRİ</h3>
                </div>
                <div class="block-content">
                    <table class="js-table-sections table table-sm table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">№</th>
                            <th>İl</th>
                            <th class="text-center" style="width: 100px;">
                                İDARƏ
                            </th>
                        </tr>
                        </thead>
                        <tbody class="js-table-sections-header">
                        @php $index = 1; @endphp
                        @foreach ($attendanceLogs as $year => $attendanceLog)
                            <tr>
                                <th class="text-center" scope="row">{{$index}}</th>
                                <td class="fs-sm">
                                    {{ $year }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.salaryEmployees.show', $year) }}"
                                           class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                           data-bs-toggle="tooltip" aria-label="View"
                                           data-bs-original-title="View" title="Bax">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @php $index++; @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
