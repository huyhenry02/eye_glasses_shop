@extends('customer.layouts.main')

@section('content')
    <section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('/customer/images/bg-01.jpg');">
        <h2 class="ltext-105 cl0 txt-center">
            Đăng ký tài khoản
        </h2>
    </section>

    <section class="bg0 p-t-104 p-b-116">
        <div class="container">
            <div class="flex-w flex-tr">
                <div class="size-210 bor10 p-lr-70 p-t-55 p-b-70 p-lr-15-lg w-full-md">
                    <form action="{{ route('auth.postRegister') }}" method="POST">
                        @csrf
                        <h4 class="mtext-105 cl2 txt-center p-b-30">
                            Tạo tài khoản mới
                        </h4>
                        <div class="bor8 m-b-20 how-pos4-parent">
                            <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30"
                                   type="text" name="full_name"
                                   placeholder="Họ và tên" required>
                            <span class="how-pos4 pointer-none">
                                <i class="zmdi zmdi-account"></i>
                            </span>
                        </div>
                        <div class="bor8 m-b-20 how-pos4-parent">
                            <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30"
                                   type="text" name="phone"
                                   placeholder="Số điện thoại" required>
                            <span class="how-pos4 pointer-none">
                                <i class="zmdi zmdi-phone"></i>
                            </span>
                        </div>
                        <div class="bor8 m-b-20 how-pos4-parent">
                            <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30"
                                   type="email" name="email"
                                   placeholder="Email" required>
                            <span class="how-pos4 pointer-none">
                                <i class="zmdi zmdi-email"></i>
                            </span>
                        </div>
                        <div class="bor8 m-b-20 how-pos4-parent">
                            <select class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30"
                                    name="gender" required
                                    style="appearance:none;-webkit-appearance:none;background:transparent;">
                                <option value="" selected disabled>Giới tính</option>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                                <option value="Khác">Khác</option>
                            </select>
                            <span class="how-pos4 pointer-none">
                                <i class="zmdi zmdi-male-female"></i>
                            </span>
                            <span style="position:absolute;right:18px;top:50%;transform:translateY(-50%);color:#888;">
                                <i class="zmdi zmdi-chevron-down"></i>
                            </span>
                        </div>

                        <div class="bor8 m-b-20 how-pos4-parent">
                            <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30"
                                   type="date" name="birthday" required
                                   style="background:transparent;">
                            <span class="how-pos4 pointer-none">
                                <i class="zmdi zmdi-calendar"></i>
                            </span>
                        </div>

                        <div class="bor8 m-b-20 how-pos4-parent">
                            <input id="passwordInput"
                                   class="stext-111 cl2 plh3 size-116 p-l-62 p-r-60"
                                   type="password" name="password"
                                   placeholder="Mật khẩu" required>
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

                        <div class="bor8 m-b-30 how-pos4-parent">
                            <textarea class="stext-111 cl2 plh3 size-120 p-l-62 p-r-30 p-tb-25"
                                      name="address" placeholder="Địa chỉ" required></textarea>
                            <span class="how-pos4 pointer-none" style="top:18px;">
                                <i class="zmdi zmdi-pin"></i>
                            </span>
                        </div>

                        <button class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer"
                                type="submit">
                            Đăng ký
                        </button>

                        <div class="p-t-20 txt-center">
                            <span class="stext-115 cl6">Đã có tài khoản?</span>
                            <a href="{{ route('auth.showLogin') }}" class="stext-115 cl2 hov-cl1 trans-04" style="margin-left:6px;">
                                Đăng nhập
                            </a>
                        </div>
                    </form>
                </div>

                <div class="size-210 bor10 flex-w flex-col-m p-lr-93 p-tb-30 p-lr-15-lg w-full-md">
                    <div class="flex-w w-full p-b-42">
                        <span class="fs-18 cl5 txt-center size-211">
                            <i class="zmdi zmdi-shield-check"></i>
                        </span>

                        <div class="size-212 p-t-2">
                            <span class="mtext-110 cl2">
                                Lợi ích khi đăng ký
                            </span>

                            <p class="stext-115 cl6 size-213 p-t-18">
                                Theo dõi đơn hàng, lưu địa chỉ nhận hàng, nhận ưu đãi dành riêng cho thành viên và thanh toán nhanh hơn.
                            </p>
                        </div>
                    </div>

                    <div class="flex-w w-full p-b-42">
                        <span class="fs-18 cl5 txt-center size-211">
                            <i class="zmdi zmdi-lock-outline"></i>
                        </span>

                        <div class="size-212 p-t-2">
                            <span class="mtext-110 cl2">
                                Bảo mật tài khoản
                            </span>

                            <p class="stext-115 cl6 size-213 p-t-18">
                                Mật khẩu của bạn được mã hóa và bảo vệ an toàn. Vui lòng không chia sẻ mật khẩu cho người khác.
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
                                Nếu bạn gặp vấn đề khi đăng ký, hãy liên hệ bộ phận hỗ trợ để được hướng dẫn nhanh.
                            </p>

                            <div class="p-t-10">
                                <a href="#" class="stext-115 cl1 hov-cl1 trans-04">
                                    <i class="zmdi zmdi-phone me-1"></i> Gọi hỗ trợ
                                </a>
                                <span class="stext-115 cl6" style="margin: 0 10px;">|</span>
                                <a href="#" class="stext-115 cl1 hov-cl1 trans-04">
                                    <i class="zmdi zmdi-email me-1"></i> Gửi email
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
        input[type="date"]::-webkit-calendar-picker-indicator { opacity: 0; }
    </style>
@endsection
