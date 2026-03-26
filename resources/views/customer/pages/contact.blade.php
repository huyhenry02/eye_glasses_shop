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
                        <h2>Liên hệ với chúng tôi</h2>
                        <p>Hỗ trợ tư vấn chọn kính, giải đáp đơn hàng và tiếp nhận mọi thắc mắc từ khách hàng</p>
                    </div>
                    <div class="page_link">
                        <a href="{{ route('customer.showIndex') }}">Trang chủ</a>
                        <a href="{{ route('customer.showContact') }}">Liên hệ</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Home Banner Area =================-->

    <!-- ================ contact section start ================= -->
    <section class="section_gap">
        <div class="container">
            <div class="d-none d-sm-block mb-5 pb-4">
                <div class="map-wrapper">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.911168077194!2d105.84318117584078!3d20.99619768889517!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ad001bdfb571%3A0x82e4c79fed76069b!2zxJDhuqFpIGjhu41jIGPDtG5nIG5naOG7hyBnaWFvIHRow7RuZyB24bqtbiB04bqjaQ!5e0!3m2!1svi!2s!4v1774538925111!5m2!1svi!2s"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h2 class="contact-title">Gửi thông tin cho chúng tôi</h2>
                </div>

                <div class="col-lg-8 mb-4 mb-lg-0">
                    <form class="form-contact contact_form"
                          action="#"
                          method="post"
                          id="contactForm"
                          novalidate="novalidate">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea class="form-control w-100"
                                              name="message"
                                              id="message"
                                              cols="30"
                                              rows="9"
                                              placeholder="Nhập nội dung cần tư vấn hoặc thắc mắc của bạn"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control"
                                           name="name"
                                           id="name"
                                           type="text"
                                           placeholder="Nhập họ và tên">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control"
                                           name="email"
                                           id="email"
                                           type="email"
                                           placeholder="Nhập địa chỉ email">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control"
                                           name="subject"
                                           id="subject"
                                           type="text"
                                           placeholder="Nhập chủ đề liên hệ">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-lg-3">
                            <button type="submit" class="main_btn">Gửi liên hệ</button>
                        </div>
                    </form>
                </div>

                <div class="col-lg-4">
                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-home"></i></span>
                        <div class="media-body">
                            <h3>Showroom Kính Mắt Việt</h3>
                            <p>123 Phố Huế, Hai Bà Trưng, Hà Nội</p>
                        </div>
                    </div>

                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-tablet"></i></span>
                        <div class="media-body">
                            <h3><a href="tel:0988888888">0988 888 888</a></h3>
                            <p>Hỗ trợ từ 8:00 đến 21:00 mỗi ngày</p>
                        </div>
                    </div>

                    <div class="media contact-info">
                        <span class="contact-info__icon"><i class="ti-email"></i></span>
                        <div class="media-body">
                            <h3><a href="mailto:hotro@kinhmatviet.vn">hotro@kinhmatviet.vn</a></h3>
                            <p>Tiếp nhận tư vấn, phản hồi và hỗ trợ đơn hàng</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ================ contact section end ================= -->
    <style>
        .map-wrapper {
            width: 100%;
            height: 420px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #eee;
        }
    </style>
@endsection
