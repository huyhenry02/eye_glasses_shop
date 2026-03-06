@extends('customer.layouts.main')
@section('content')
    <section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('/customer/images/bg-01.jpg');">
        <h2 class="ltext-105 cl0 txt-center">
            Đăng nhập
        </h2>
    </section>

    <section class="bg0 p-t-104 p-b-116">
        <div class="container">
            <div class="flex-w flex-tr">
                <div class="size-210 bor10 p-lr-70 p-t-55 p-b-70 p-lr-15-lg w-full-md">
                    <form action="{{ route('auth.postLogin') }}" method="POST">
                        @csrf
                        <h4 class="mtext-105 cl2 txt-center p-b-30">
                            Đăng nhập tài khoản
                        </h4>

                        <div class="bor8 m-b-20 how-pos4-parent">
                            <input
                                class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30"
                                type="text"
                                name="phone"
                                placeholder="Số điện thoại"
                                required
                            >
                            <span class="how-pos4 pointer-none">
                                <i class="zmdi zmdi-phone"></i>
                            </span>
                        </div>

                        <div class="bor8 m-b-30 how-pos4-parent">
                            <input
                                id="passwordInput"
                                class="stext-111 cl2 plh3 size-116 p-l-62 p-r-60"
                                type="password"
                                name="password"
                                placeholder="Mật khẩu"
                                required
                            >
                            <span class="how-pos4 pointer-none">
                                <i class="zmdi zmdi-lock"></i>
                            </span>

                            <button type="button"
                                    id="togglePassword"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                                           background:transparent;border:none;color:#666;cursor:pointer;">
                                <i class="zmdi zmdi-eye"></i>
                            </button>
                        </div>

                        <button
                            class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer"
                            type="submit"
                        >
                            Đăng nhập
                        </button>

                        <div class="p-t-20 txt-center">
                            <span class="stext-115 cl6">Chưa có tài khoản?</span>
                            <a href="{{ route('auth.showRegister') }}" class="stext-115 cl2 hov-cl1 trans-04" style="margin-left:6px;">
                                Đăng ký ngay
                            </a>
                        </div>

                        <div class="p-t-10 txt-center">
                            <a href="#" class="stext-115 cl1 hov-cl1 trans-04">
                                Quên mật khẩu?
                            </a>
                        </div>
                    </form>
                </div>

                <div class="size-210 bor10 flex-w flex-col-m p-lr-93 p-tb-30 p-lr-15-lg w-full-md">
                    <div class="flex-w w-full p-b-42">
                        <span class="fs-18 cl5 txt-center size-211">
                            <i class="zmdi zmdi-account-circle"></i>
                        </span>

                        <div class="size-212 p-t-2">
                            <span class="mtext-110 cl2">
                                Tài khoản khách hàng
                            </span>

                            <p class="stext-115 cl6 size-213 p-t-18">
                                Đăng nhập để theo dõi đơn hàng, quản lý thông tin cá nhân và nhận ưu đãi dành riêng cho thành viên.
                            </p>
                        </div>
                    </div>

                    <div class="flex-w w-full p-b-42">
                        <span class="fs-18 cl5 txt-center size-211">
                            <i class="zmdi zmdi-shield-security"></i>
                        </span>

                        <div class="size-212 p-t-2">
                            <span class="mtext-110 cl2">
                                Bảo mật & an toàn
                            </span>

                            <p class="stext-115 cl6 size-213 p-t-18">
                                Chúng tôi cam kết bảo mật thông tin đăng nhập của bạn bằng các tiêu chuẩn an toàn cao.
                            </p>
                        </div>
                    </div>

                    <div class="flex-w w-full">
                        <span class="fs-18 cl5 txt-center size-211">
                            <i class="zmdi zmdi-help-outline"></i>
                        </span>

                        <div class="size-212 p-t-2">
                            <span class="mtext-110 cl2">
                                Cần hỗ trợ?
                            </span>

                            <p class="stext-115 cl6 size-213 p-t-18">
                                Nếu bạn gặp khó khăn khi đăng nhập, vui lòng liên hệ bộ phận hỗ trợ.
                            </p>

                            <div class="p-t-10">
                                <a href="#" class="stext-115 cl1 hov-cl1 trans-04">
                                    <i class="zmdi zmdi-phone"></i> Gọi hỗ trợ
                                </a>
                                <span class="stext-115 cl6" style="margin: 0 10px;">|</span>
                                <a href="#" class="stext-115 cl1 hov-cl1 trans-04">
                                    <i class="zmdi zmdi-email"></i> Gửi email
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('passwordInput');
            const btn = document.getElementById('togglePassword');
            const icon = btn.querySelector('i');

            btn.addEventListener('click', function () {
                const isPass = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPass ? 'text' : 'password');
                icon.className = isPass ? 'zmdi zmdi-eye-off' : 'zmdi zmdi-eye';
            });
        });
    </script>

    <style>
        .how-pos4 i { font-size: 18px; color: #666; }
        #togglePassword i { font-size: 20px; }
    </style>
@endsection
