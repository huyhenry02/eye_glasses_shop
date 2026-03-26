<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="icon" href="/customer/img/favicon.png" type="image/png"/>
    <title>Kính mắt</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/customer/css/bootstrap.css"/>
    <link rel="stylesheet" href="/customer/vendors/linericon/style.css"/>
    <link rel="stylesheet" href="/customer/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="/customer/css/themify-icons.css"/>
    <link rel="stylesheet" href="/customer/css/flaticon.css"/>
    <link rel="stylesheet" href="/customer/vendors/owl-carousel/owl.carousel.min.css"/>
    <link rel="stylesheet" href="/customer/vendors/lightbox/simpleLightbox.css"/>
    <link rel="stylesheet" href="/customer/vendors/nice-select/css/nice-select.css"/>
    <link rel="stylesheet" href="/customer/vendors/animate-css/animate.css"/>
    <link rel="stylesheet" href="/customer/vendors/jquery-ui/jquery-ui.css"/>
    <!-- main css -->
    <link rel="stylesheet" href="/customer/css/style.css"/>
    <link rel="stylesheet" href="/customer/css/responsive.css"/>
</head>

<body>
<!--================Header Menu Area =================-->
@include('customer.layouts.header')
<!--================Header Menu Area =================-->

<!--================Home Banner Area =================-->
<section class="home_banner_area mb-40">
    <div class="banner_inner d-flex align-items-center">
        <div class="container">
            <div class="banner_content row">
                <div class="col-lg-12">
                    <p class="sub text-uppercase">Bộ sưu tập kính mới</p>
                    <h3>Tôn lên phong cách <br/>với mẫu kính phù hợp</h3>
                    <h4>Kính thời trang, kính chống nắng và gọng kính hiện đại cho nam, nữ và unisex.</h4>
                    <a class="main_btn mt-40" href="{{ route('customer.showProducts') }}">Xem bộ sưu tập</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Home Banner Area =================-->

<!-- Start feature Area -->
<section class="feature-area section_gap_bottom_custom">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="single-feature">
                    <a href="#" class="title">
                        <i class="flaticon-money"></i>
                        <h3>Cam kết chính hãng</h3>
                    </a>
                    <p>Sản phẩm rõ nguồn gốc, chất lượng đảm bảo</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="single-feature">
                    <a href="#" class="title">
                        <i class="flaticon-truck"></i>
                        <h3>Giao hàng toàn quốc</h3>
                    </a>
                    <p>Đóng gói cẩn thận, giao nhanh trên toàn quốc</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="single-feature">
                    <a href="#" class="title">
                        <i class="flaticon-support"></i>
                        <h3>Tư vấn tận tâm</h3>
                    </a>
                    <p>Hỗ trợ chọn kính phù hợp với gương mặt và nhu cầu</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="single-feature">
                    <a href="#" class="title">
                        <i class="flaticon-blockchain"></i>
                        <h3>Thanh toán an toàn</h3>
                    </a>
                    <p>Hỗ trợ COD và thanh toán trực tuyến bảo mật</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End feature Area -->

<!--================ Feature Product Area =================-->
<section class="feature_product_area section_gap_bottom_custom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="main_title">
                    <h2><span>Sản phẩm nổi bật</span></h2>
                    <p>Những mẫu kính được nhiều khách hàng quan tâm và lựa chọn</p>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($featuredProducts as $product)
                <div class="col-lg-4 col-md-6">
                    <div class="single-product">
                        <div class="product-img">
                            <img class="img-fluid w-100"
                                 src="{{ $product->image ?: '/customer/img/product/feature-product/f-p-1.jpg' }}"
                                 alt="{{ $product->name ?? 'Sản phẩm kính mắt' }}"/>
                            <div class="p_icon">
                                <a href="{{ route('customer.showProductDetail', $product->id) }}">
                                    <i class="ti-eye"></i>
                                </a>
                                <a href="{{ route('customer.showProductDetail', $product->id) }}">
                                    <i class="ti-heart"></i>
                                </a>
                                <a href="{{ route('customer.showProductDetail', $product->id) }}">
                                    <i class="ti-shopping-cart"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-btm">
                            <a href="{{ route('customer.showProductDetail', $product->id) }}" class="d-block">
                                <h4>{{ $product->name ?? 'Kính mắt thời trang' }}</h4>
                            </a>
                            @if(!empty($product->brand) || !empty($product->gender))
                                <div class="mt-2">
                                    <small class="text-muted">
                                        {{ $product->brand ?? 'Kính cao cấp' }}
                                        @if(!empty($product->gender))
                                            - {{ $product->gender }}
                                        @endif
                                    </small>
                                </div>
                            @endif
                            <div class="mt-3">
                                @if(!empty($product->discount_price) && $product->discount_price < $product->price)
                                    <span class="mr-4">{{ number_format($product->discount_price) }}đ</span>
                                    <del>{{ number_format($product->price) }}đ</del>
                                @else
                                    <span class="mr-4">{{ number_format($product->price ?? 0) }}đ</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center">Hiện chưa có sản phẩm nổi bật.</div>
                </div>
            @endforelse
        </div>
    </div>
</section>
<!--================ End Feature Product Area =================-->

<!--================ Offer Area =================-->
<section class="offer_area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-lg-4 col-lg-6 text-center">
                <div class="offer_content">
                    <h3 class="text-uppercase mb-40">Bộ sưu tập kính bán chạy</h3>
                    <h2 class="text-uppercase">Ưu đãi hấp dẫn</h2>
                    <a href="{{ route('customer.showProducts') }}" class="main_btn mb-20 mt-5">Khám phá ngay</a>
                    <p>Nhiều mẫu kính đẹp, hiện đại và dễ phối phong cách hằng ngày</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================ End Offer Area =================-->

<!--================ New Product Area =================-->
<section class="new_product_area section_gap_top section_gap_bottom_custom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="main_title">
                    <h2><span>Sản phẩm mới</span></h2>
                    <p>Cập nhật các mẫu kính mới nhất dành cho phong cách hiện đại</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                @if($highlightProduct)
                    <div class="new_product">
                        <h5 class="text-uppercase">
                            {{ $highlightProduct->category->name ?? 'Bộ sưu tập mới' }}
                        </h5>
                        <h3 class="text-uppercase">{{ $highlightProduct->name ?? 'Kính mắt thời trang' }}</h3>
                        <div class="product-img">
                            <img class="img-fluid"
                                 src="{{ $highlightProduct->image ?: '/customer/img/product/new-product/new-product1.png' }}"
                                 alt="{{ $highlightProduct->name ?? 'Sản phẩm kính' }}"/>
                        </div>
                        <h4>
                            @if(!empty($highlightProduct->discount_price) && $highlightProduct->discount_price < $highlightProduct->price)
                                {{ number_format($highlightProduct->discount_price) }}đ
                            @else
                                {{ number_format($highlightProduct->price ?? 0) }}đ
                            @endif
                        </h4>
                        <a href="{{ route('customer.showProductDetail', $highlightProduct->id) }}" class="main_btn">
                            Xem chi tiết
                        </a>
                    </div>
                @else
                    <div class="new_product">
                        <h5 class="text-uppercase">Bộ sưu tập mới</h5>
                        <h3 class="text-uppercase">Kính mắt thời trang</h3>
                        <div class="product-img">
                            <img class="img-fluid" src="/customer/img/product/new-product/new-product1.png"
                                 alt="Sản phẩm kính"/>
                        </div>
                        <h4>Liên hệ</h4>
                        <a href="{{ route('customer.showProducts') }}" class="main_btn">Xem sản phẩm</a>
                    </div>
                @endif
            </div>

            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="row">
                    @forelse($newArrivalProducts as $product)
                        <div class="col-lg-6 col-md-6">
                            <div class="single-product">
                                <div class="product-img">
                                    <img class="img-fluid w-100"
                                         src="{{ $product->image ?: '/customer/img/product/new-product/n1.jpg' }}"
                                         alt="{{ $product->name ?? 'Sản phẩm kính' }}"/>
                                    <div class="p_icon">
                                        <a href="{{ route('customer.showProductDetail', $product->id) }}">
                                            <i class="ti-eye"></i>
                                        </a>
                                        <a href="{{ route('customer.showProductDetail', $product->id) }}">
                                            <i class="ti-heart"></i>
                                        </a>
                                        <a href="{{ route('customer.showProductDetail', $product->id) }}">
                                            <i class="ti-shopping-cart"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="product-btm">
                                    <a href="{{ route('customer.showProductDetail', $product->id) }}" class="d-block">
                                        <h4>{{ $product->name ?? 'Kính mắt thời trang' }}</h4>
                                    </a>
                                    <div class="mt-3">
                                        @if(!empty($product->discount_price) && $product->discount_price < $product->price)
                                            <span class="mr-4">{{ number_format($product->discount_price) }}đ</span>
                                            <del>{{ number_format($product->price) }}đ</del>
                                        @else
                                            <span class="mr-4">{{ number_format($product->price ?? 0) }}đ</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center">Hiện chưa có sản phẩm mới.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
<!--================ End New Product Area =================-->

<!--================ Inspired Product Area =================-->
<section class="inspired_product_area section_gap_bottom_custom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="main_title">
                    <h2><span>Có thể bạn sẽ thích</span></h2>
                    <p>Những mẫu kính phù hợp cho nhiều phong cách và nhu cầu sử dụng khác nhau</p>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($inspiredProducts as $product)
                <div class="col-lg-3 col-md-6">
                    <div class="single-product">
                        <div class="product-img">
                            <img class="img-fluid w-100"
                                 src="{{ $product->image ?: '/customer/img/product/inspired-product/i1.jpg' }}"
                                 alt="{{ $product->name ?? 'Sản phẩm kính' }}"/>
                            <div class="p_icon">
                                <a href="{{ route('customer.showProductDetail', $product->id) }}">
                                    <i class="ti-eye"></i>
                                </a>
                                <a href="{{ route('customer.showProductDetail', $product->id) }}">
                                    <i class="ti-heart"></i>
                                </a>
                                <a href="{{ route('customer.showProductDetail', $product->id) }}">
                                    <i class="ti-shopping-cart"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-btm">
                            <a href="{{ route('customer.showProductDetail', $product->id) }}" class="d-block">
                                <h4>{{ $product->name ?? 'Kính mắt thời trang' }}</h4>
                            </a>
                            <div class="mt-2">
                                <small class="text-muted">
                                    {{ $product->category->name ?? 'Kính mắt' }}
                                </small>
                            </div>
                            <div class="mt-3">
                                @if(!empty($product->discount_price) && $product->discount_price < $product->price)
                                    <span class="mr-4">{{ number_format($product->discount_price) }}đ</span>
                                    <del>{{ number_format($product->price) }}đ</del>
                                @else
                                    <span class="mr-4">{{ number_format($product->price ?? 0) }}đ</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center">Hiện chưa có sản phẩm gợi ý.</div>
                </div>
            @endforelse
        </div>
    </div>
</section>
<!--================ End Inspired Product Area =================-->

<!--================ Start Blog Area =================-->
<section class="blog-area section-gap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="main_title">
                    <h2><span>Tin tức & tư vấn</span></h2>
                    <p>Một số bài viết hữu ích giúp bạn chọn kính phù hợp hơn</p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach( $selectedBlogs as $key => $blog )
                <div class="col-lg-4 col-md-6">
                    <div class="single-blog">
                        <div class="thumb">
                            <img class="img-fluid" src="{{ $blog['image'] }}" alt="{{ $blog['title'] }}">
                        </div>
                        <div class="short_details">
                            <div class="meta-top d-flex">
                                <a href="#">By Admin</a>
                                <a href="#"><i class="ti-comments-smiley"></i>2 bình luận</a>
                            </div>
                            <a class="d-block" href="#">
                                <h4>{{ $blog['title'] }}</h4>
                            </a>
                            <a href="{{ route('customer.showBlogDetail', $blog['slug']) }}" class="blog_btn">Xem thêm <span class="ml-2 ti-arrow-right"></span></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!--================ End Blog Area =================-->

<!--================ start footer Area  =================-->
@include('customer.layouts.footer')
<!--================ End footer Area  =================-->

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="/customer/js/jquery-3.2.1.min.js"></script>
<script src="/customer/js/popper.js"></script>
<script src="/customer/js/bootstrap.min.js"></script>
<script src="/customer/js/stellar.js"></script>
<script src="/customer/vendors/lightbox/simpleLightbox.min.js"></script>
<script src="/customer/vendors/nice-select/js/jquery.nice-select.min.js"></script>
<script src="/customer/vendors/isotope/imagesloaded.pkgd.min.js"></script>
<script src="/customer/vendors/isotope/isotope-min.js"></script>
<script src="/customer/vendors/owl-carousel/owl.carousel.min.js"></script>
<script src="/customer/js/jquery.ajaxchimp.min.js"></script>
<script src="/customer/vendors/counter-up/jquery.waypoints.min.js"></script>
<script src="/customer/vendors/counter-up/jquery.counterup.js"></script>
<script src="/customer/js/mail-script.js"></script>
<script src="/customer/js/theme.js"></script>
</body>

</html>
