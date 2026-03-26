<header class="header_area">
    <div class="top_menu">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="float-left">
                        <p>Hotline: 0988 888 888</p>
                        <p>Email: hotro@kinhmatviet.vn</p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="float-right">
                        <ul class="right_side">
                            <li>
                                <a href="{{ route('customer.orders.index') }}">
                                    Tra cứu đơn hàng
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('customer.showContact') }}">
                                    Liên hệ
                                </a>
                            </li>

                            @auth
                                <li>
                                    <a href="{{ route('auth.logout') }}">
                                        Đăng xuất
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('auth.showLogin') }}">
                                        Đăng nhập
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('auth.showRegister') }}">
                                        Đăng ký
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main_menu">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light w-100">
                <a class="navbar-brand logo_h" href="{{ route('customer.showIndex') }}">
                    <img src="/customer/img/logo.png" alt="Kính Mắt Việt" />
                </a>

                <button
                    class="navbar-toggler"
                    type="button"
                    data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div
                    class="collapse navbar-collapse offset w-100"
                    id="navbarSupportedContent"
                >
                    <div class="row w-100 mr-0 align-items-center">
                        <div class="col-lg-7 pr-0">
                            <ul class="nav navbar-nav center_nav pull-right">
                                <li class="nav-item {{ request()->routeIs('customer.showIndex') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('customer.showIndex') }}">Trang chủ</a>
                                </li>

                                <li class="nav-item {{ request()->routeIs('customer.showProducts') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('customer.showProducts') }}">Sản phẩm</a>
                                </li>

                                <li class="nav-item {{ request()->routeIs('customer.showBlog', 'customer.showBlogDetail') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('customer.showBlog') }}">Tin tức</a>
                                </li>

                                <li class="nav-item {{ request()->routeIs('customer.showContact') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('customer.showContact') }}">Liên hệ</a>
                                </li>

                                @auth
                                    <li class="nav-item {{ request()->routeIs('customer.orders.index') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('customer.orders.index') }}">Đơn hàng của tôi</a>
                                    </li>
                                @endauth
                            </ul>
                        </div>

                        <div class="col-lg-5 pr-0">
                            <ul class="nav navbar-nav navbar-right right_nav pull-right align-items-center">
                                @auth
                                    <li class="nav-item position-relative">
                                        <a href="{{ route('customer.showCart') }}" class="icons" title="Giỏ hàng">
                                            <i class="ti-shopping-cart"></i>
                                            @if(!empty($cartCountGlobal) && $cartCountGlobal > 0)
                                                <span style="
                                                position: absolute;
                                                top: -6px;
                                                right: -10px;
                                                min-width: 18px;
                                                height: 18px;
                                                line-height: 18px;
                                                border-radius: 50%;
                                                background: #e53935;
                                                color: #fff;
                                                font-size: 11px;
                                                text-align: center;
                                                padding: 0 4px;
                                                font-weight: 600;
                                            ">
                                                {{ $cartCountGlobal }}
                                            </span>
                                            @endif
                                        </a>
                                    </li>
                                    <li class="nav-item submenu dropdown">
                                        <a
                                            href="#"
                                            class="icons dropdown-toggle"
                                            data-toggle="dropdown"
                                            role="button"
                                            aria-haspopup="true"
                                            aria-expanded="false"
                                            title="Tài khoản"
                                        >
                                            <i class="ti-user" aria-hidden="true"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            @if(auth()->user()->user_type === 'customer')
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('customer.orders.index') }}">
                                                        Đơn hàng của tôi
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('customer.showCart') }}">
                                                        Giỏ hàng
                                                    </a>
                                                </li>
                                            @endif

                                            @if(auth()->user()->user_type === 'admin')
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('admin.product.showIndex') }}">
                                                        Quản trị hệ thống
                                                    </a>
                                                </li>
                                            @endif

                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('auth.logout') }}">
                                                    Đăng xuất
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                @else
                                    <li class="nav-item submenu dropdown">
                                        <a
                                            href="#"
                                            class="icons dropdown-toggle"
                                            data-toggle="dropdown"
                                            role="button"
                                            aria-haspopup="true"
                                            aria-expanded="false"
                                            title="Tài khoản"
                                        >
                                            <i class="ti-user" aria-hidden="true"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('auth.showLogin') }}">
                                                    Đăng nhập
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('auth.showRegister') }}">
                                                    Đăng ký
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
