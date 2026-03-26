<footer class="footer-area section_gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 single-footer-widget">
                <h4>Danh mục nổi bật</h4>
                <ul>
                    <li><a href="{{ route('customer.showProducts') }}">Kính thời trang</a></li>
                    <li><a href="{{ route('customer.showProducts') }}">Kính chống nắng</a></li>
                    <li><a href="{{ route('customer.showProducts') }}">Gọng kính nam</a></li>
                    <li><a href="{{ route('customer.showProducts') }}">Gọng kính nữ</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 single-footer-widget">
                <h4>Liên kết nhanh</h4>
                <ul>
                    <li><a href="{{ route('customer.showIndex') }}">Trang chủ</a></li>
                    <li><a href="{{ route('customer.showProducts') }}">Sản phẩm</a></li>
                    <li><a href="{{ route('customer.showBlog') }}">Tin tức</a></li>
                    <li><a href="{{ route('customer.showContact') }}">Liên hệ</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 single-footer-widget">
                <h4>Hỗ trợ khách hàng</h4>
                <ul>
                    <li><a href="{{ route('customer.orders.index') }}">Tra cứu đơn hàng</a></li>
                    <li><a href="{{ route('customer.showCart') }}">Giỏ hàng</a></li>
                    <li><a href="{{ route('customer.showCheckout') }}">Thanh toán</a></li>
                    <li><a href="{{ route('customer.showContact') }}">Tư vấn chọn kính</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 single-footer-widget">
                <h4>Nhận ưu đãi mới</h4>
                <p>Đăng ký email để nhận thông tin khuyến mãi và mẫu kính mới nhất.</p>
                <div class="form-wrap" id="mc_embed_signup">
                    <form action="#" method="post" class="form-inline">
                        @csrf
                        <input
                            class="form-control"
                            name="email"
                            placeholder="Nhập địa chỉ email"
                            onfocus="this.placeholder = ''"
                            onblur="this.placeholder = 'Nhập địa chỉ email'"
                            required
                            type="email"
                        >
                        <button class="click-btn btn btn-default">Đăng ký</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="footer-bottom row align-items-center">

            <div class="col-lg-4 col-md-12 footer-social">
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-instagram"></i></a>
                <a href="#"><i class="fa fa-youtube-play"></i></a>
                <a href="#"><i class="fa fa-phone"></i></a>
            </div>
        </div>
    </div>
</footer>
