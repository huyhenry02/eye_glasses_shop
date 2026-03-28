<header class="nxl-header">
    @php
        $currentRoute = request()->route();
        $pageTitle = $currentRoute?->defaults['page_title'] ?? 'Quản trị hệ thống';
        $pageParent = $currentRoute?->defaults['page_parent'] ?? 'Quản trị';
        $pageCurrent = $currentRoute?->defaults['page_current'] ?? null;
        $pageAction = $currentRoute?->defaults['page_action'] ?? null;
    @endphp

    <div class="header-wrapper">
        <div class="header-left d-flex align-items-center gap-4">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10 mb-0">{{ $pageTitle }}</h5>
                </div>
            </div>
        </div>

        <div class="header-right ms-auto">
            <div class="d-flex align-items-center">
                <div class="nxl-h-item d-none d-sm-flex">
                    <div class="full-screen-switcher">
                        <a href="javascript:void(0);" class="nxl-head-link me-0" onclick="$('body').fullScreenHelper('toggle');">
                            <i class="feather-maximize maximize"></i>
                            <i class="feather-minimize minimize"></i>
                        </a>
                    </div>
                </div>

                <div class="nxl-h-item dark-light-theme">
                    <a href="javascript:void(0);" class="nxl-head-link me-0 dark-button">
                        <i class="feather-moon"></i>
                    </a>
                    <a href="javascript:void(0);" class="nxl-head-link me-0 light-button" style="display: none">
                        <i class="feather-sun"></i>
                    </a>
                </div>

                <div class="dropdown nxl-h-item">
                    <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                        <img src="/assets/images/avatar/1.png" alt="user-image" class="img-fluid user-avtar me-0" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <img src="/assets/images/avatar/1.png" alt="user-image" class="img-fluid user-avtar" />
                                <div>
                                    <h6 class="text-dark mb-0">
                                        {{ auth()->user()?->employee?->full_name ?? auth()->user()?->phone ?? 'Tài khoản' }}
                                        <span class="badge bg-soft-success text-success ms-1">PRO</span>
                                    </h6>
                                    <span class="fs-12 fw-medium text-muted">
                                        {{ auth()->user()?->employee?->email ?? '' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('customer.showIndex') }}" class="dropdown-item">
                            <i class="feather-settings"></i>
                            <span>Web shop</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <a href="{{ route('auth.logout') }}" class="dropdown-item">
                            <i class="feather-log-out"></i>
                            <span>Đăng xuất</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
