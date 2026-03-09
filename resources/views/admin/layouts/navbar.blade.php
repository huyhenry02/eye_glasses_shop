<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="index.html" class="b-brand">
                <img src="/assets/images/logo-full.png" alt="" class="logo logo-lg" />
                <img src="/assets/images/logo-abbr.png" alt="" class="logo logo-sm" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Quản trị hệ thống</label>
                </li>
                <li class="nxl-item nxl-hasmenu nxl-trigger">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Quản lý nhân viên</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.employee.showIndex') }}">Danh sách nhân viên</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.employee.showCreate') }}">Thêm mới nhân viên</a></li>
                    </ul>
                </li>
                <li class="nxl-item nxl-hasmenu nxl-trigger">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Quản lý khách hàng</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.customer.showIndex') }}">Danh sách khách hàng</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.customer.showCreate') }}">Thêm mới khách hàng</a></li>
                    </ul>
                </li>
                <li class="nxl-item nxl-hasmenu nxl-trigger">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Quản lý danh mục</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.category.showIndex') }}">Danh sách danh mục</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.category.showCreate') }}">Thêm mới danh mục</a></li>
                    </ul>
                </li>
                <li class="nxl-item nxl-hasmenu nxl-trigger">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Quản lý sản phẩm</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.product.showIndex') }}">Danh sách sản phẩm</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.product.showCreate') }}">Thêm mới sản phẩm</a></li>
                    </ul>
                </li>
                <li class="nxl-item nxl-hasmenu nxl-trigger">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Quản lý đơn hàng</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="{{ route('admin.order.showIndex') }}">Danh sách đơn hàng</a></li>
                    </ul>
                </li>
                <li class="nxl-item nxl-hasmenu nxl-trigger">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Quản lý hóa đơn</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link" href="">Danh sách hóa đơn</a></li>
                        <li class="nxl-item"><a class="nxl-link" href="">Thêm mới hóa đơn</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
