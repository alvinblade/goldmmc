<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Accounting - GOLD MMC</title>
    <meta name="description" content="Accounting - GOLD MMC">
    <meta name="author" content="GOLD MMC">
    <!-- Open Graph Meta -->
    <meta property="og:title" content="Accounting - GOLD MMC">
    <meta property="og:site_name" content="Accounting - GOLD MMC">
    <meta property="og:description"
          content="Accounting - Gold MMC">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="{{ asset('assets/admin/media/favicons/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"
          href="{{ asset('assets/admin/media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180"
          href="{{ asset('assets/admin/media/favicons/apple-touch-icon-180x180.png') }}">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- OneUI framework -->
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/admin/css/oneui.min.css') }}">
    <!-- END Stylesheets -->
</head>

<body>
<!-- Page Container -->
<div id="page-container">
    <!-- Main Container -->
    <main id="main-container">
        <!-- Page Content -->
        <div class="hero-static d-flex align-items-center">
            <div class="w-100">
                <!-- Sign In Section -->
                <div class="bg-body-extra-light">
                    <div class="content content-full">
                        <div class="row g-0 justify-content-center">
                            <div class="py-4 px-4 px-lg-5">
                                <!-- Header -->
                                <div class="text-center">
                                    <img src="{{ asset('assets/admin/media/logo.jpg') }}" alt="Gold MMC"
                                         style="width: 130px;">
                                    <h3>
                                        Xidmət göstəriləcək şirkəti seçin
                                        <hr>
                                    </h3>
                                    @error('company_id')
                                    <h6 class="text-danger">
                                        {{$message}}
                                    </h6>
                                    @enderror
                                </div>
                                <!-- END Header -->
                                <!-- Sign In Form -->
                                <form action="{{ route('admin.select-company.select') }}" method="POST">
                                    @csrf
                                    <div class="py-3">
                                        <div class="row items-push">
                                            @foreach($companies as $company)
                                                <div class="col-lg-3">
                                                    <div class="form-check form-block">
                                                        <input type="radio" class="form-check-input"
                                                               id="example-radio-block{{$company->id}}"
                                                               name="company_id" value="{{ $company->id }}">
                                                        <label class="form-check-label"
                                                               for="example-radio-block{{$company->id}}">
                          <span class="d-block fw-normal text-center my-3">
                            <span class="fs-4 fw-semibold">
                                {{ $company->company_name }}
                            </span>
                          </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-lg-6 col-xxl-5">
                                            <button type="submit" class="btn w-100 btn-alt-primary">
                                                <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> Daxil ol
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- END Sign In Form -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Sign In Section -->

                <!-- Footer -->
                <div class="fs-sm text-center text-muted py-3">
                    <strong>GOLD MMC</strong> &copy; {{ date('Y') }}
                </div>
                <!-- END Footer -->
            </div>
        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->
</div>
<!-- END Page Container -->
<script src="{{ asset('assets/admin/js/oneui.app.min.js') }}"></script>

<!-- jQuery (required for jQuery Validation plugin) -->
<script src="{{ asset('assets/admin/js/lib/jquery.min.js') }}"></script>

<!-- Page JS Plugins -->
<script src="{{ asset('assets/admin/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

<!-- Page JS Code -->
<script src="{{ asset('assets/admin/js/pages/op_auth_signin.min.js') }}"></script>
</body>
</html>
