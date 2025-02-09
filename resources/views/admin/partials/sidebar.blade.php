<!-- Sidebar -->
<nav id="sidebar" aria-label="Main Navigation">
    <!-- Side Header -->
    <div class="content-header">
        <!-- Logo -->
        <a class="fw-semibold text-dual" href="{{ url('/admin') }}">
            <span class="smini-visible">
                <i class="fa fa-circle-notch text-primary"></i>
            </span>
            <span class="smini-hide fs-5 tracking-wider">
                GOLD <span class="fw-normal">LLC</span>
            </span>
        </a>
        <!-- END Logo -->

        <!-- Options -->
        <div>
            <!-- Close Sidebar, Visible only on mobile screens -->
            <a class="d-lg-none btn btn-sm btn-alt-secondary ms-1" data-toggle="layout" data-action="sidebar_close"
               href="javascript:void(0)">
                <i class="fa fa-fw fa-times"></i>
            </a>
            <!-- END Close Sidebar -->
        </div>
        <!-- END Options -->
    </div>
    <!-- END Side Header -->

    <!-- Sidebar Scrolling -->
    <div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side">
            <ul class="nav-main">
                <li class="nav-main-item">
                    <a class="nav-main-link active" href="{{ url('/admin') }}">
                        <i class="nav-main-link-icon si si-speedometer"></i>
                        <span class="nav-main-link-name">Admin Paneli</span>
                    </a>
                </li>
                @if(auth()->user()->hasRole(['leading_expert', 'department_head']))
                    <li class="nav-main-heading">İSTİFADƏÇİLƏR & ŞİRKƏTLƏR</li>
                    <li class="nav-main-item">
                        <a class="nav-main-link" href="{{ route('admin.users.index') }}">
                            <i class="nav-main-link-icon fas fa-user"></i>
                            <span class="nav-main-link-name">
                            İstifadəçilər
                        </span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link" href="{{ route('admin.companies.index') }}">
                            <i class="nav-main-link-icon fas fa-building-user"></i>
                            <span class="nav-main-link-name">
                            Şirkətlər
                        </span>
                        </a>
                    </li>
                @endif
                <li class="nav-main-heading">VƏZİFƏLƏR & İŞÇİLƏR</li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link"
                       aria-expanded="false" href="{{ route('admin.positions.index') }}">
                        <i class="nav-main-link-icon fas fa-star-half-stroke"></i>
                        <span class="nav-main-link-name">
                            Vəzifələr
                        </span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link"
                       aria-expanded="false" href="{{ route('admin.employees.index') }}">
                        <i class="nav-main-link-icon fas fa-user-tie"></i>
                        <span class="nav-main-link-name">
                            İşçilər
                        </span>
                    </a>
                </li>
                <li class="nav-main-heading">TABEL ŞABLONU & CƏDVƏLİ</li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link"
                       aria-expanded="false" href="{{ route('admin.positions.index') }}">
                        <i class="nav-main-link-icon fas fa-calendar-days"></i>
                        <span class="nav-main-link-name">
                            Tabel şablonu
                        </span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link"
                       aria-expanded="false" href="{{ route('admin.positions.index') }}">
                        <i class="nav-main-link-icon fas fa-calendar-check"></i>
                        <span class="nav-main-link-name">
                            Tabel cədvəli
                        </span>
                    </a>
                </li>
                <li class="nav-main-heading">ƏMRLƏR</li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link"
                       aria-expanded="false" href="{{ route('admin.awardOrders.index') }}">
                        <i class="nav-main-link-icon fas fa-list-alt"></i>
                        <span class="nav-main-link-name">
                            Mükafat əmrləri
                        </span>
                    </a>
                </li>
                <hr>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link"
                       aria-expanded="false" href="{{ route('admin.rentalContracts.index') }}">
                        <i class="nav-main-link-icon fas fa-file-signature"></i>
                        <span class="nav-main-link-name">
                            Kirayə müqavilələri
                        </span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link"
                       aria-expanded="false" href="{{ route('admin.activityCodes.index') }}">
                        <i class="nav-main-link-icon fas fa-barcode"></i>
                        <span class="nav-main-link-name">
                            Fəaliyyət kodları
                        </span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link"
                       aria-expanded="false" href="{{ route('admin.envelopes.index') }}">
                        <i class="nav-main-link-icon fas fa-envelope"></i>
                        <span class="nav-main-link-name">
                            Məktublar
                        </span>
                    </a>
                </li>
                <li class="nav-main-heading">VALYUTA MƏZƏNNƏSİ</li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link"
                       aria-expanded="false" href="{{ route('currencies.index') }}">
                        <i class="nav-main-link-icon fas fa-chart-bar"></i>
                        <span class="nav-main-link-name">
                            Valyuta məzənnəsi
                        </span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- END Side Navigation -->
    </div>
    <!-- END Sidebar Scrolling -->
</nav>
<!-- END Sidebar -->
