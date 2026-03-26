@extends('customer.layouts.main')
@section('content')
    <section class="banner_area">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content d-md-flex justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0">
                        <h2>Chi tiết bài viết</h2>
                        <p>{{ $blog['title'] }}</p>
                    </div>
                    <div class="page_link">
                        <a href="{{ route('customer.showIndex') }}">Trang chủ</a>
                        <a href="{{ route('customer.showBlog') }}">Tin tức</a>
                        <a href="{{ route('customer.showBlogDetail', $blog['slug']) }}">Chi tiết</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blog_area single-post-area section_gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 posts-list">
                    <div class="single-post">
                        <div class="feature-img">
                            <img class="img-fluid" src="{{ $blog['image'] }}" alt="{{ $blog['title'] }}">
                        </div>

                        <div class="blog_details">
                            <h2>{{ $blog['title'] }}</h2>
                            <ul class="blog-info-link mt-3 mb-4">
                                <li><a href="#"><i class="ti-user"></i> {{ $blog['category'] }}</a></li>
                                <li><a href="#"><i class="ti-calendar"></i> {{ \Carbon\Carbon::parse($blog['published_at'])->format('d/m/Y') }}</a></li>
                            </ul>

                            @foreach($blog['content'] as $index => $paragraph)
                                @if($index === 1)
                                    <p class="excert">{{ $paragraph }}</p>
                                @else
                                    <p>{{ $paragraph }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    @if($relatedBlogs->count() > 0)
                        <div class="navigation-top">
                            <div class="navigation-area">
                                <div class="row">
                                    @foreach($relatedBlogs as $relatedBlog)
                                        <div class="col-lg-6 col-md-6 col-12 nav-left flex-row d-flex justify-content-start align-items-center mb-3">
                                            <div class="thumb">
                                                <a href="{{ route('customer.showBlogDetail', $relatedBlog['slug']) }}">
                                                    <img class="img-fluid" src="{{ $relatedBlog['image'] }}" alt="{{ $relatedBlog['title'] }}" style="width: 90px; height: 70px; object-fit: cover;">
                                                </a>
                                            </div>
                                            <div class="detials ml-3">
                                                <p>Bài viết liên quan</p>
                                                <a href="{{ route('customer.showBlogDetail', $relatedBlog['slug']) }}">
                                                    <h4>{{ $relatedBlog['title'] }}</h4>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
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
                                    <img src="{{ $recentBlog['image'] }}" alt="{{ $recentBlog['title'] }}" style="width: 80px; height: 60px; object-fit: cover;">
                                    <div class="media-body">
                                        <a href="{{ route('customer.showBlogDetail', $recentBlog['slug']) }}">
                                            <h3>{{ $recentBlog['title'] }}</h3>
                                        </a>
                                        <p>{{ \Carbon\Carbon::parse($recentBlog['published_at'])->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </aside>

                        <aside class="single_sidebar_widget tag_cloud_widget">
                            <h4 class="widget_title">Từ khóa</h4>
                            <ul class="list">
                                @foreach($blog['tags'] as $tag)
                                    <li><a href="#">{{ $tag }}</a></li>
                                @endforeach
                            </ul>
                        </aside>

                        <aside class="single_sidebar_widget newsletter_widget">
                            <h4 class="widget_title">Nhận tin mới</h4>
                            <p>Theo dõi thêm nhiều bài viết hữu ích về kính mắt và xu hướng mới.</p>
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
