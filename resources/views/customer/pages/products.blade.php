@extends('customer.layouts.main')
@section('content')
    <!--================Home Banner Area =================-->
    <section class="banner_area">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content d-md-flex justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0">
                        <h2>Danh sách sản phẩm</h2>
                        <p>Khám phá các mẫu kính mắt thời trang, hiện đại và phù hợp với nhiều phong cách</p>
                    </div>
                    <div class="page_link">
                        <a href="{{ route('customer.showIndex') }}">Trang chủ</a>
                        <a href="{{ route('customer.showProducts') }}">Sản phẩm</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Home Banner Area =================-->

    <!--================Category Product Area =================-->
    <section class="cat_product_area section_gap">
        <div class="container">
            <form method="GET" action="{{ route('customer.showProducts') }}" id="filterForm">
                <div class="row flex-row-reverse">
                    <div class="col-lg-9">
                        <div class="product_top_bar">
                            <div class="left_dorp d-flex flex-wrap align-items-center">
                                <select class="sorting mr-10" name="sort" onchange="document.getElementById('filterForm').submit()">
                                    <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                    <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                    <option value="name_asc" {{ $sort == 'name_asc' ? 'selected' : '' }}>Tên A - Z</option>
                                    <option value="name_desc" {{ $sort == 'name_desc' ? 'selected' : '' }}>Tên Z - A</option>
                                </select>

                                <select class="show" name="per_page" onchange="document.getElementById('filterForm').submit()">
                                    <option value="9" {{ $perPage == 9 ? 'selected' : '' }}>Hiển thị 9</option>
                                    <option value="12" {{ $perPage == 12 ? 'selected' : '' }}>Hiển thị 12</option>
                                    <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>Hiển thị 15</option>
                                    <option value="18" {{ $perPage == 18 ? 'selected' : '' }}>Hiển thị 18</option>
                                </select>
                            </div>
                        </div>

                        <div class="latest_product_inner">
                            <div class="row">
                                @forelse($products as $product)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="single-product">
                                            <div class="product-img">
                                                <img
                                                    class="card-img"
                                                    src="{{ $product->image ?: '/customer/img/product/inspired-product/i1.jpg' }}"
                                                    alt="{{ $product->name ?? 'Sản phẩm kính mắt' }}"
                                                />
                                                <div class="p_icon">
                                                    <a href="{{ route('customer.showProductDetail', $product->id) }}" title="Xem chi tiết">
                                                        <i class="ti-eye"></i>
                                                    </a>
                                                    <a href="#" title="Thêm vào giỏ hàng">
                                                        <i class="ti-shopping-cart"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="product-btm">
                                                <a href="{{ route('customer.showProductDetail', $product->id) }}" class="d-block">
                                                    <h4>{{ $product->name ?? 'Kính mắt thời trang' }}</h4>
                                                </a>

                                                @if(!empty($product->category?->name))
                                                    <div class="mb-2">
                                                        <small class="text-muted">{{ $product->category->name }}</small>
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
                                        <div class="text-center py-5">
                                            <h5>Không có sản phẩm phù hợp</h5>
                                            <p>Vui lòng thử lại với bộ lọc khác.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        @if($products->hasPages())
                            <div class="custom-pagination-wrapper">
                                <div class="custom-pagination-info">
                                    Hiển thị {{ $products->firstItem() }} - {{ $products->lastItem() }} trong tổng số {{ $products->total() }} sản phẩm
                                </div>

                                <div class="custom-pagination-box">
                                    <ul class="custom-pagination-list">
                                        @if ($products->onFirstPage())
                                            <li class="disabled">
                                                <span><i class="ti-angle-left"></i></span>
                                            </li>
                                        @else
                                            <li>
                                                <a href="{{ $products->previousPageUrl() }}" aria-label="Trang trước">
                                                    <i class="ti-angle-left"></i>
                                                </a>
                                            </li>
                                        @endif

                                        @php
                                            $currentPage = $products->currentPage();
                                            $lastPage = $products->lastPage();
                                            $start = max($currentPage - 1, 1);
                                            $end = min($currentPage + 1, $lastPage);

                                            if ($currentPage <= 2) {
                                                $end = min(3, $lastPage);
                                            }

                                            if ($currentPage >= $lastPage - 1) {
                                                $start = max($lastPage - 2, 1);
                                            }
                                        @endphp

                                        @if($start > 1)
                                            <li>
                                                <a href="{{ $products->url(1) }}">1</a>
                                            </li>
                                            @if($start > 2)
                                                <li class="dots"><span>...</span></li>
                                            @endif
                                        @endif

                                        @for($page = $start; $page <= $end; $page++)
                                            <li class="{{ $page == $currentPage ? 'active' : '' }}">
                                                <a href="{{ $products->url($page) }}">{{ $page }}</a>
                                            </li>
                                        @endfor

                                        @if($end < $lastPage)
                                            @if($end < $lastPage - 1)
                                                <li class="dots"><span>...</span></li>
                                            @endif
                                            <li>
                                                <a href="{{ $products->url($lastPage) }}">{{ $lastPage }}</a>
                                            </li>
                                        @endif

                                        @if ($products->hasMorePages())
                                            <li>
                                                <a href="{{ $products->nextPageUrl() }}" aria-label="Trang sau">
                                                    <i class="ti-angle-right"></i>
                                                </a>
                                            </li>
                                        @else
                                            <li class="disabled">
                                                <span><i class="ti-angle-right"></i></span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-3">
                        <div class="left_sidebar_area">
                            <aside class="left_widgets p_filter_widgets">
                                <div class="l_w_title">
                                    <h3>Danh mục sản phẩm</h3>
                                </div>
                                <div class="widgets_inner">
                                    <ul class="list">
                                        <li class="{{ empty($selectedCategory) ? 'active' : '' }}">
                                            <a href="{{ route('customer.showProducts', array_merge(request()->except('page', 'category_id'), ['category_id' => null])) }}">
                                                Tất cả sản phẩm
                                            </a>
                                        </li>

                                        @foreach($categories as $category)
                                            <li class="{{ (string)$selectedCategory === (string)$category->id ? 'active' : '' }}">
                                                <a href="{{ route('customer.showProducts', array_merge(request()->except('page'), ['category_id' => $category->id])) }}">
                                                    {{ $category->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </aside>

                            <aside class="left_widgets p_filter_widgets">
                                <div class="l_w_title">
                                    <h3>Lọc theo giá</h3>
                                </div>
                                <div class="widgets_inner">
                                    <ul class="list">
                                        <li class="{{ empty($selectedPriceRange) ? 'active' : '' }}">
                                            <a href="{{ route('customer.showProducts', array_merge(request()->except('page', 'price_range'), ['price_range' => null])) }}">
                                                Tất cả mức giá
                                            </a>
                                        </li>
                                        <li class="{{ $selectedPriceRange === 'under_500' ? 'active' : '' }}">
                                            <a href="{{ route('customer.showProducts', array_merge(request()->except('page'), ['price_range' => 'under_500'])) }}">
                                                Dưới 500.000đ
                                            </a>
                                        </li>
                                        <li class="{{ $selectedPriceRange === '500_1000' ? 'active' : '' }}">
                                            <a href="{{ route('customer.showProducts', array_merge(request()->except('page'), ['price_range' => '500_1000'])) }}">
                                                500.000đ - 1.000.000đ
                                            </a>
                                        </li>
                                        <li class="{{ $selectedPriceRange === '1000_2000' ? 'active' : '' }}">
                                            <a href="{{ route('customer.showProducts', array_merge(request()->except('page'), ['price_range' => '1000_2000'])) }}">
                                                1.000.000đ - 2.000.000đ
                                            </a>
                                        </li>
                                        <li class="{{ $selectedPriceRange === 'over_2000' ? 'active' : '' }}">
                                            <a href="{{ route('customer.showProducts', array_merge(request()->except('page'), ['price_range' => 'over_2000'])) }}">
                                                Trên 2.000.000đ
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </aside>

                            <aside class="left_widgets p_filter_widgets">
                                <div class="l_w_title">
                                    <h3>Thông tin lọc</h3>
                                </div>
                                <div class="widgets_inner">
                                    <p style="margin-bottom: 8px;">
                                        Tổng sản phẩm: <strong>{{ $products->total() }}</strong>
                                    </p>
                                    <p style="margin-bottom: 8px;">
                                        Đang hiển thị: <strong>{{ $products->count() }}</strong>
                                    </p>
                                    <a href="{{ route('customer.showProducts') }}" class="genric-btn default-border circle small">
                                        Xóa bộ lọc
                                    </a>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <style>
            .custom-pagination-wrapper {
                margin-top: 45px;
                text-align: center;
            }

            .custom-pagination-info {
                font-size: 14px;
                color: #777;
                margin-bottom: 18px;
                line-height: 1.6;
            }

            .custom-pagination-box {
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .custom-pagination-list {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 0;
                margin: 0;
                list-style: none;
                flex-wrap: wrap;
            }

            .custom-pagination-list li {
                margin: 0;
            }

            .custom-pagination-list li a,
            .custom-pagination-list li span {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                border: 1px solid #e5e5e5;
                background: #fff;
                color: #222;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 15px;
                font-weight: 500;
                text-decoration: none;
                transition: all 0.25s ease;
            }

            .custom-pagination-list li a:hover {
                background: #71cd14;
                border-color: #71cd14;
                color: #fff;
                transform: translateY(-2px);
            }

            .custom-pagination-list li.active a {
                background: #71cd14;
                border-color: #71cd14;
                color: #fff;
                box-shadow: 0 8px 18px rgba(113, 205, 20, 0.25);
            }

            .custom-pagination-list li.disabled span {
                background: #f7f7f7;
                border-color: #ececec;
                color: #bdbdbd;
                cursor: not-allowed;
            }

            .custom-pagination-list li.dots span {
                border: none;
                background: transparent;
                width: auto;
                height: auto;
                color: #999;
                font-size: 16px;
            }

            .custom-pagination-list li a i,
            .custom-pagination-list li span i {
                font-size: 14px;
                font-weight: 600;
            }

            @media (max-width: 576px) {
                .custom-pagination-list {
                    gap: 8px;
                }

                .custom-pagination-list li a,
                .custom-pagination-list li span {
                    width: 38px;
                    height: 38px;
                    font-size: 13px;
                }

                .custom-pagination-info {
                    font-size: 13px;
                    margin-bottom: 14px;
                }
            }
        </style>
    </section>
@endsection
