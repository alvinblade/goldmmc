<!-- Header -->
<header id="page-header">
    <!-- Header Content -->
    <div class="content-header">
        <!-- Left Section -->
        <div class="d-flex align-items-center">
            <!-- Toggle Sidebar -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
            <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-lg-none" data-toggle="layout"
                    data-action="sidebar_toggle">
                <i class="fa fa-fw fa-bars"></i>
            </button>
            <!-- END Toggle Sidebar -->

            <!-- Toggle Mini Sidebar -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
            <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-none d-lg-inline-block"
                    data-toggle="layout"
                    data-action="sidebar_mini_toggle">
                <i class="fa fa-fw fa-ellipsis-v"></i>
            </button>
            <!-- END Toggle Mini Sidebar -->

        </div>
        <!-- END Left Section -->

        <!-- Right Section -->
        <div class="d-flex align-items-center">
            <!-- User Dropdown -->
            <div class="dropdown d-inline-block ms-2">
                <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center"
                        id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    <span class="d-none d-sm-inline-block ms-2">{{ auth()->user()->name }}</span>
                    <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ms-1 mt-1"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0"
                     aria-labelledby="page-header-user-dropdown">
                    <div class="p-3 text-center bg-body-light border-bottom rounded-top">
                        <p class="mt-2 mb-0 fw-medium">{{ auth()->user()->name . ' ' . auth()->user()->surname }}</p>
                        <p class="mb-0 text-muted fs-sm fw-medium">
                            {{ auth()->user()->roles->first()->display_name_az }}
                        </p>
                    </div>
                    <div class="p-2">
                        <a class="dropdown-item d-flex align-items-center justify-content-between"
                           href="{{ route('admin.users.edit', auth()->id()) }}">
                            <span class="fs-sm fw-medium">Hesab</span>
                        </a>
                    </div>
                    <div role="separator" class="dropdown-divider m-0"></div>
                    <div class="p-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <a class="dropdown-item d-flex align-items-center justify-content-between"
                               href="javascript:void(0)" onclick="this.parentElement.submit()">
                                <span class="fs-sm fw-medium">Çıxış</span>
                            </a>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END User Dropdown -->

            <!-- Notifications Dropdown -->
            <div class="dropdown d-inline-block ms-2">
                <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-notifications-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-fw fa-building-user"></i>
                    <span class="text-primary">•</span>
                </button>
                <form class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0 fs-sm"
                      aria-labelledby="page-header-notifications-dropdown"
                      action="{{ route('admin.select-company.select') }}" method="POST"
                      id="selectCompanyForm">
                    @csrf
                    <div class="p-2 bg-body-light border-bottom text-center rounded-top">
                        <h5 class="dropdown-header text-uppercase">
                            XİDMƏT GÖSTƏRİLƏN ŞİRKƏTLƏR
                        </h5>
                    </div>
                    <ul class="nav-items mb-0" style="height: 400px;overflow-y: scroll;">
                        @foreach($servedCompanies as $id => $companyName)
                            @if(request()->header('company-id') == $id)
                                <li class="bg-success-light">
                                    <a class="text-dark d-flex py-2"
                                       href="javascript:void(0)">
                                        <div class="flex-shrink-0 me-2 ms-3">
                                            <i class="fa fa-fw fa-check-circle text-success"></i>
                                        </div>
                                        <div class="flex-grow-1 pe-2">
                                            <div class="fw-semibold">
                                                {{ $companyName }}
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @else
                                <li>
                                    <input type="radio" name="company_id" value="{{ $id }}" class="d-none"/>
                                    <a onclick="selectCompanyForm(this.previousElementSibling)"
                                       class="text-dark d-flex py-2" href="javascript:void(0)">
                                        <div class="flex-shrink-0 me-2 ms-3">
                                            <i class="fa fa-fw fa-plus-circle text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1 pe-2">
                                            <div class="fw-semibold">
                                                {{ $companyName }}
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </form>
            </div>
            <!-- END Notifications Dropdown -->
        </div>
        <!-- END Right Section -->
    </div>
    <!-- END Header Content -->
    <!-- Header Loader -->
    <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
    <div id="page-header-loader" class="overlay-header bg-body-extra-light">
        <div class="content-header">
            <div class="w-100 text-center">
                <i class="fa fa-fw fa-circle-notch fa-spin"></i>
            </div>
        </div>
    </div>
    <!-- END Header Loader -->
</header>
<!-- END Header -->
<script>
    function selectCompanyForm(radioInput) {
        radioInput.checked = true;

        document.querySelector('#selectCompanyForm').submit()
    }
</script>
