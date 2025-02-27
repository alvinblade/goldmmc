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
                @foreach($files as $fileKey => $fileValues)
                    @if(!empty($fileValues['files']))
                        <div class="col-6 col-md-4 col-xxl-2">
                            <a class="block block-rounded text-center block-link-pop"
                               style="cursor: pointer;"
                               data-bs-toggle="offcanvas"
                               data-bs-target="#offcanvasRight{{$fileKey}}"
                               aria-controls="offcanvasRight{{$fileKey}}">
                                <div class="block-content block-content-full ratio ratio-16x9">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div>
                                            <i class="far fa-2x fa-file-alt text-dark"></i>
                                            <div class="fs-sm fw-semibold mt-3 text-dark">
                                                {{ $fileValues['title'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- Offcanvas Right -->
                        <div class="offcanvas offcanvas-end bg-body-extra-light p-0" tabindex="-1"
                             id="offcanvasRight{{$fileKey}}"
                             aria-labelledby="offcanvasRightLabel">
                            <div class="offcanvas-header bg-body-light">
                                <h5 class="offcanvas-title" id="offcanvasRightLabel">{{ $fileValues['title'] }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                        aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                @foreach($fileValues['files'] as $file)
                                    <a href="{{$file['path']}}" target="_blank"
                                       class="btn btn-alt-info w-100 mb-2">
                                        <i class="fas fa-file"></i>
                                        {{$file['original_name']}}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <!-- END Offcanvas Right -->
                    @endif
                @endforeach
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
