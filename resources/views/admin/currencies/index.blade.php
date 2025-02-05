@extends('admin.index')


@section('title')
    Valyuta Məzənnəsi
@endsection
@section('page_styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/js/plugins/flatpickr/flatpickr.min.css') }}">
@endsection
@section('content')
    <main id="main-container">
        <!-- Hero -->
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h1 class="h3 fw-bold mb-2">
                            Valyuta Məzənnəsi
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <div class="content w-100">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">VALYUTA MƏZƏNNƏSİ CƏDVƏLİ</h3>
                </div>
                <div class="block-content">
                    <table class="table table-hover table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">№</th>
                            <th>AD</th>
                            <th>KOD</th>
                            <th>SİMBOL</th>
                            <th>MƏZƏNNƏ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($currencies as $currency)
                            <tr>
                                <th class="text-center"
                                    scope="row">{{ $currencies->firstItem() + $loop->index }}</th>
                                <td class="fw-semibold fs-sm">
                                    {{ $currency->title }}
                                </td>
                                <td class="fw-semibold fs-sm">
                                    {{ $currency->code }}
                                </td>
                                <td class="fw-semibold fs-sm">
                                    {{ $currency->symbol }}
                                </td>
                                <td class="fw-semibold fs-sm">
                                    {{ $currency->rate }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $currencies->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </main>
@endsection
