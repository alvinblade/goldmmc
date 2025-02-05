@extends('admin.index')
@section('title')
    Admin Paneli
@endsection
@section('page_styles')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <link rel="stylesheet" href="{{ asset('assets/admin/js/plugins/flatpickr/flatpickr.min.css') }}">
@endsection
@section('content')
    <!-- Main Container -->
    <main id="main-container">
        <!-- Hero -->
        <div class="content">
            <div
                class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
                <div class="flex-grow-1 mb-1 mb-md-0">
                    <h1 class="h3 fw-bold mb-2">
                        {{ auth()->user()->name }} {{ auth()->user()->surname }}
                        - {{ auth()->user()->roles->first()->display_name_az }}
                    </h1>
                    <h2 class="h6 fw-medium fw-medium text-muted mb-0">
                        Xidmət olunacaq şirkət: {{ $selectedCompany->company_name }}
                    </h2>
                </div>
            </div>
        </div>
        <!-- END Hero -->
        <!-- Page Content -->
        <div class="content">
            <!-- Overview -->
            <div class="row items-push">
                <div class="col-sm-12 col-lg-6">
                    <!-- Pending Orders -->
                    <div class="block block-rounded d-flex flex-column h-100 mb-0">
                        <div
                            class="block-content block-content-full flex-grow-1 d-flex
                             justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="fs-3 fw-bold">0</dt>
                                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                    ÜMUMİ BORCLAR
                                </dd>
                            </dl>
                            <div class="item item-rounded-lg bg-body-light">
                                <i class="fas fa-coins fs-3 text-danger"></i>
                            </div>
                        </div>
                    </div>
                    <!-- END Pending Orders -->
                </div>
                <div class="col-sm-12 col-lg-6">
                    <!-- Pending Orders -->
                    <div class="block block-rounded d-flex flex-column h-100 mb-0">
                        <div
                            class="block-content block-content-full flex-grow-1 d-flex
                             justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="fs-3 fw-bold">0</dt>
                                <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">
                                    ÜMUMİ GƏLİR
                                </dd>
                            </dl>
                            <div class="item item-rounded-lg bg-body-light">
                                <i class="fas fa-coins fs-3 text-danger"></i>
                            </div>
                        </div>
                    </div>
                    <!-- END Pending Orders -->
                </div>
            </div>
            <!-- END Overview -->
        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->
@endsection
@section('page_scripts')
    <script src="{{asset('assets/admin/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/az.js"></script>
    <script>
        flatpickr.localize(flatpickr.l10ns.az);
        One.helpersOnLoad(['js-flatpickr', 'one-table-tools-sections']);
    </script>
@endsection
