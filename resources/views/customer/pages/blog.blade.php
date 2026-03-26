@php use Carbon\Carbon; @endphp
@extends('customer.layouts.main')
@section('content')
    <section class="banner_area">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content d-md-flex justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0">
                        <h2>Tin tức & tư vấn</h2>
                        <p>Chia sẻ kinh nghiệm chọn kính, bảo quản kính và cập nhật xu hướng kính mắt mới</p>
                    </div>
                    <div class="page_link">
                        <a href="{{ route('customer.showIndex') }}">Trang chủ</a>
                        <a href="{{ route('customer.showBlog') }}">Tin tức</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blog_area section_gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="blog_left_sidebar">
                        @foreach($blogs as $blog)
                            @php
                                $date = Carbon::parse($blog['published_at']);
                            @endphp

                            <article class="blog_item">
                                <div class="blog_item_img">
                                    <img class="card-img rounded-0" src="{{ $blog['image'] }}"
                                         alt="{{ $blog['title'] }}">
                                    <a href="{{ route('customer.showBlogDetail', $blog['slug']) }}"
                                       class="blog_item_date">
                                        <h3>{{ $date->format('d') }}</h3>
                                        <p>Th{{ $date->format('m') }}</p>
                                    </a>
                                </div>

                                <div class="blog_details">
                                    <a class="d-inline-block"
                                       href="{{ route('customer.showBlogDetail', $blog['slug']) }}">
                                        <h2>{{ $blog['title'] }}</h2>
                                    </a>
                                    <p>{{ $blog['excerpt'] }}</p>
                                    <ul class="blog-info-link">
                                        <li>
                                            <a href="{{ route('customer.showBlogDetail', $blog['slug']) }}">
                                                <i class="ti-user"></i> {{ $blog['category'] }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('customer.showBlogDetail', $blog['slug']) }}">
                                                <i class="ti-book"></i> Xem chi tiết
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="blog_right_sidebar">
                        <aside class="single_sidebar_widget post_category_widget">
                            <h4 class="widget_title">Chuyên mục</h4>
                            <ul class="list cat-list">
                                @foreach($blogCategories as $categoryName => $count)
                                    <li>
                                        <a href="#" class="d-flex">
                                            <p>{{ $categoryName }}</p>
                                            <p>({{ $count }})</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </aside>

                        <aside class="single_sidebar_widget popular_post_widget">
                            <h3 class="widget_title">Bài viết mới</h3>
                            @foreach($recentBlogs as $recentBlog)
                                <div class="media post_item">
                                    <img src="{{ $recentBlog['image'] }}" alt="{{ $recentBlog['title'] }}"
                                         style="width: 80px; height: 60px; object-fit: cover;">
                                    <div class="media-body">
                                        <a href="{{ route('customer.showBlogDetail', $recentBlog['slug']) }}">
                                            <h3>{{ $recentBlog['title'] }}</h3>
                                        </a>
                                        <p>{{ Carbon::parse($recentBlog['published_at'])->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </aside>

                        <aside class="single_sidebar_widget tag_cloud_widget">
                            <h4 class="widget_title">Từ khóa</h4>
                            <ul class="list">
                                <li><a href="#">kính mắt</a></li>
                                <li><a href="#">gọng kính</a></li>
                                <li><a href="#">kính chống nắng</a></li>
                                <li><a href="#">ánh sáng xanh</a></li>
                                <li><a href="#">bảo quản kính</a></li>
                                <li><a href="#">xu hướng</a></li>
                                <li><a href="#">thời trang</a></li>
                                <li><a href="#">tư vấn</a></li>
                            </ul>
                        </aside>

                        <aside class="single_sidebar_widget newsletter_widget">
                            <h4 class="widget_title">Nhận tin mới</h4>
                            <p>Đăng ký để nhận các bài viết mới nhất về kính mắt và ưu đãi từ cửa hàng.</p>
                            <form action="#">
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Nhập email của bạn" required>
                                </div>
                                <button class="main_btn rounded-0 w-100" type="submit">Đăng ký</button>
                            </form>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
