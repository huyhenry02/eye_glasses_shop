@extends('customer.layouts.main')
@section('content')
    <!--================Home Banner Area =================-->
    <section class="banner_area">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div
                    class="banner_content d-md-flex justify-content-between align-items-center"
                >
                    <div class="mb-3 mb-md-0">
                        <h2>Đăng nhập tài khoản</h2>
                        <p>Đăng nhập để mua sắm kính mắt, theo dõi đơn hàng và nhận ưu đãi mới nhất</p>
                    </div>
                    <div class="page_link">
                        <a href="{{ route('customer.showIndex') }}">Trang chủ</a>
                        <a href="{{ route('auth.showLogin') }}">Đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Home Banner Area =================-->

    <!-- ================ login section start ================= -->
    <section class="section_gap">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="contact-title">Thông tin đăng nhập</h2>
                </div>

                <div class="col-lg-8 mb-4 mb-lg-0">
                    <form class="form-contact contact_form"
                          action="{{ route('auth.postLogin') }}"
                          method="POST"
                          id="loginForm"
                          novalidate="novalidate">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control"
                                           name="phone"
                                           id="phone"
                                           type="text"
                                           placeholder="Nhập số điện thoại"
                                           value="{{ old('phone') }}">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control"
                                           name="password"
                                           id="password"
                                           type="password"
                                           placeholder="Nhập mật khẩu">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-lg-3">
                            <button type="submit" class="main_btn">
                                Đăng nhập
                            </button>
                        </div>

                        <div class="mt-3">
                            <span>Chưa có tài khoản?</span>
                            <a href="{{ route('auth.showRegister') }}">Đăng ký ngay</a>
                        </div>
                    </form>
                </div>

                <div class="col-lg-4">
                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-user"></i></span>
                        <div class="media-body">
                            <h3>Đăng nhập nhanh chóng</h3>
                            <p>Truy cập tài khoản để tiếp tục mua sắm thuận tiện hơn</p>
                        </div>
                    </div>

                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-shopping-cart"></i></span>
                        <div class="media-body">
                            <h3>Quản lý đơn hàng dễ dàng</h3>
                            <p>Theo dõi trạng thái đơn hàng và lịch sử mua sắm của bạn</p>
                        </div>
                    </div>

                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-gift"></i></span>
                        <div class="media-body">
                            <h3>Nhận ưu đãi thành viên</h3>
                            <p>Cập nhật nhanh chương trình khuyến mãi và sản phẩm kính mới</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ================ login section end ================= -->
@endsection
