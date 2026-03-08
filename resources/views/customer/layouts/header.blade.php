<header class="header-v4">
    <div class="container-menu-desktop">
        <div class="wrap-menu-desktop how-shadow1">
            <nav class="limiter-menu-desktop container">
                <a href="{{ route('customer.showIndex') }}" class="logo">
                    <img src="/customer/images/icons/logo-01.png" alt="IMG-LOGO">
                </a>

                <div class="menu-desktop">
                    <ul class="main-menu">
                        <li class="{{ request()->routeIs('customer.showIndex') ? 'active-menu' : '' }}">
                            <a href="{{ route('customer.showIndex') }}">Trang chủ</a>
                        </li>

                        <li class="{{ request()->routeIs('customer.showProducts') ? 'active-menu' : '' }}">
                            <a href="{{ route('customer.showProducts') }}">Sản phẩm</a>
                        </li>

                        <li>
                            <a href="">Giới thiệu</a>
                        </li>

                        <li>
                            <a href="">Liên hệ</a>
                        </li>
                    </ul>
                </div>

                <div class="wrap-icon-header flex-w flex-r-m">
                    @auth
                        <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
                             data-notify="{{ $cartCountGlobal ?? 0 }}">
                            <i class="zmdi zmdi-shopping-cart"></i>
                        </div>
                    @endauth

                    <div class="account-dropdown-wrapper" id="accountDropdown">
                        <button type="button"
                                class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 account-toggle"
                                id="accountToggle">
                            <i class="zmdi zmdi-account-o"></i>
                        </button>

                        <ul class="account-dropdown-menu" id="accountMenu">
                            @guest
                                <li>
                                    <a href="{{ route('auth.showLogin') }}">Đăng nhập</a>
                                </li>
                                <li>
                                    <a href="{{ route('auth.showRegister') }}">Đăng ký</a>
                                </li>
                            @endguest

                            @auth
                                <li>
                                    <a href="{{ route('customer.orders.index') }}">
                                        Đơn hàng của tôi
                                    </a>
                                </li>

                                @if(auth()->user()->user_type === 'admin')
                                    <li>
                                        <a href="{{ route('admin.customer.showIndex') }}">
                                            Quản trị hệ thống
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <a href="{{ route('auth.logout') }}">
                                        Đăng xuất
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
<style>
    .wrap-icon-header {
        align-items: center;
    }

    .account-dropdown-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        margin-left: 6px;
    }

    .account-toggle {
        border: none;
        background: transparent;
        outline: none;
        cursor: pointer;
    }

    .account-toggle:focus {
        outline: none;
    }

    .account-dropdown-menu {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        min-width: 220px;
        background: #fff;
        border: 1px solid #e6e6e6;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        padding: 8px 0;
        margin: 0;
        list-style: none;
        display: none;
        z-index: 9999;
        border-radius: 6px;
    }

    .account-dropdown-menu.show {
        display: block;
    }

    .account-dropdown-menu li {
        margin: 0;
        padding: 0;
    }

    .account-dropdown-menu li a {
        display: block;
        padding: 10px 18px;
        color: #333;
        font-size: 14px;
        line-height: 1.4;
        text-decoration: none;
        white-space: nowrap;
    }

    .account-dropdown-menu li a:hover {
        background: #f7f7f7;
        color: #717fe0;
    }
</style>
<script src="/customer/vendor/jquery/jquery-3.2.1.min.js"></script>
<script>
    $(function () {
        $('#accountToggle').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $('#accountMenu').toggleClass('show');
        });

        $('#accountMenu').on('click', function (e) {
            e.stopPropagation();
        });

        $(document).on('click', function () {
            $('#accountMenu').removeClass('show');
        });
    });
</script>
