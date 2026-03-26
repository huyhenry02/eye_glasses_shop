@extends('customer.layouts.main')
@section('content')
    <section class="banner_area">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content d-md-flex justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0">
                        <h2>Đăng ký tài khoản</h2>
                        <p>Tạo tài khoản để mua sắm kính mắt nhanh chóng và thuận tiện hơn</p>
                    </div>
                    <div class="page_link">
                        <a href="{{ route('customer.showIndex') }}">Trang chủ</a>
                        <a href="{{ route('auth.showRegister') }}">Đăng ký</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section_gap">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="contact-title">Thông tin đăng ký</h2>
                </div>

                <div class="col-lg-8 mb-4 mb-lg-0">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form class="form-contact contact_form"
                          action="{{ route('auth.postRegister') }}"
                          method="POST"
                          id="registerForm"
                          novalidate="novalidate">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control"
                                           name="full_name"
                                           id="full_name"
                                           type="text"
                                           placeholder="Nhập họ và tên"
                                           value="{{ old('full_name') }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control"
                                           name="phone"
                                           id="phone"
                                           type="text"
                                           placeholder="Nhập số điện thoại"
                                           value="{{ old('phone') }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control"
                                           name="email"
                                           id="email"
                                           type="email"
                                           placeholder="Nhập địa chỉ email"
                                           value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">Chọn giới tính</option>
                                        <option value="Nam" {{ old('gender') == 'Nam' ? 'selected' : '' }}>Nam</option>
                                        <option value="Nữ" {{ old('gender') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                        <option value="Khác" {{ old('gender') == 'Khác' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control"
                                           name="birthday"
                                           id="birthday"
                                           type="date"
                                           value="{{ old('birthday') }}">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control"
                                           name="address"
                                           id="address"
                                           type="text"
                                           placeholder="Nhập địa chỉ"
                                           value="{{ old('address') }}">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group position-relative">
                                    <input class="form-control pr-5"
                                           name="password"
                                           id="register_password"
                                           type="password"
                                           placeholder="Nhập mật khẩu">
                                    <span class="toggle-password"
                                          data-target="register_password"
                                          style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer; z-index:10;">
                                        <i class="ti-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group position-relative">
                                    <input class="form-control pr-5"
                                           name="password_confirmation"
                                           id="register_password_confirmation"
                                           type="password"
                                           placeholder="Nhập lại mật khẩu">
                                    <span class="toggle-password"
                                          data-target="register_password_confirmation"
                                          style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer; z-index:10;">
                                        <i class="ti-eye"></i>
                                    </span>
                                </div>
                                <small id="register-password-message" style="display:block; margin-top:6px;"></small>
                            </div>
                        </div>

                        <div class="form-group mt-lg-3">
                            <button type="submit" class="main_btn">
                                Đăng ký ngay
                            </button>
                        </div>

                        <div class="mt-3">
                            <span>Đã có tài khoản?</span>
                            <a href="{{ route('auth.showLogin') }}">Đăng nhập tại đây</a>
                        </div>
                    </form>
                </div>

                <div class="col-lg-4">
                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-user"></i></span>
                        <div class="media-body">
                            <h3>Tạo tài khoản nhanh chóng</h3>
                            <p>Chỉ vài bước để bắt đầu mua sắm kính mắt dễ dàng</p>
                        </div>
                    </div>

                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-shopping-cart"></i></span>
                        <div class="media-body">
                            <h3>Mua hàng thuận tiện</h3>
                            <p>Lưu thông tin để đặt hàng và theo dõi đơn hàng nhanh hơn</p>
                        </div>
                    </div>

                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-gift"></i></span>
                        <div class="media-body">
                            <h3>Nhận nhiều ưu đãi</h3>
                            <p>Cập nhật nhanh các chương trình khuyến mãi và sản phẩm mới</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButtons = document.querySelectorAll('.toggle-password');

            toggleButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('ti-eye');
                        icon.classList.add('ti-eye-close');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('ti-eye-close');
                        icon.classList.add('ti-eye');
                    }
                });
            });

            const registerForm = document.getElementById('registerForm');
            const password = document.getElementById('register_password');
            const passwordConfirmation = document.getElementById('register_password_confirmation');
            const message = document.getElementById('register-password-message');

            function checkPasswordMatch() {
                if (passwordConfirmation.value === '') {
                    message.textContent = '';
                    return true;
                }

                if (password.value === passwordConfirmation.value) {
                    message.textContent = 'Mật khẩu nhập lại khớp.';
                    message.style.color = 'green';
                    return true;
                } else {
                    message.textContent = 'Mật khẩu nhập lại không khớp.';
                    message.style.color = 'red';
                    return false;
                }
            }

            password.addEventListener('input', checkPasswordMatch);
            passwordConfirmation.addEventListener('input', checkPasswordMatch);

            registerForm.addEventListener('submit', function (e) {
                if (!checkPasswordMatch()) {
                    e.preventDefault();
                    alert('Mật khẩu nhập lại không khớp.');
                }
            });
        });
    </script>
@endsection
