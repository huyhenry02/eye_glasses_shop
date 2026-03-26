@extends('customer.layouts.main')
@section('content')

    <!--================Banner =================-->
    <section class="banner_area">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content d-md-flex justify-content-between align-items-center">
                    <div>
                        <h2>Chi tiết sản phẩm</h2>
                    </div>
                    <div class="page_link">
                        <a href="{{ route('customer.showIndex') }}">Trang chủ</a>
                        <a href="{{ route('customer.showProducts') }}">Sản phẩm</a>
                        <a href="#">Chi tiết</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $images = collect([
            $product->image,
            $product->image_detail_1,
            $product->image_detail_2,
            $product->image_detail_3
        ])->filter();

        $price = $product->discount_price ?? $product->price;
    @endphp

        <!--================Product Area =================-->
    <div class="product_image_area">
        <div class="container">
            <div class="row s_product_inner">

                <!-- IMAGE -->
                <div class="col-lg-6">

                    <div class="main-image">
                        <img id="mainProductImage"
                             src="{{ $images->first() }}"
                             class="img-fluid w-100">
                    </div>

                    <!-- THUMBNAIL -->
                    @if($images->count() > 1)
                        <div class="thumbnail-list mt-3 d-flex">
                            @foreach($images as $img)
                                <img src="{{ $img }}"
                                     class="thumb-item"
                                     onclick="changeImage(this)">
                            @endforeach
                        </div>
                    @endif

                </div>

                <!-- INFO -->
                <div class="col-lg-5 offset-lg-1">
                    <div class="s_product_text">

                        <h3>{{ $product->name }}</h3>

                        <h2>
                            {{ number_format($price) }}đ
                            @if($product->discount_price)
                                <span class="old-price">
                                {{ number_format($product->price) }}đ
                            </span>
                            @endif
                        </h2>

                        <ul class="list">
                            @if($product->category)
                                <li><span>Danh mục:</span> {{ $product->category->name }}</li>
                            @endif

                            @if($product->brand)
                                <li><span>Thương hiệu:</span> {{ $product->brand }}</li>
                            @endif

                            <li>
                                <span>Tình trạng:</span>
                                {{ $product->stock_quantity > 0 ? 'Còn hàng' : 'Hết hàng' }}
                            </li>
                        </ul>

                        <p>{{ $product->description }}</p>

                        <div class="card_area">
                            <a class="main_btn" href="javascript:void(0)">
                                Thêm vào giỏ hàng
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!--================TAB =================-->
    <section class="product_description_area">
        <div class="container">

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#desc">
                        Mô tả
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#info">
                        Thông tin chi tiết
                    </a>
                </li>
            </ul>

            <div class="tab-content">

                <!-- MÔ TẢ -->
                <div class="tab-pane fade show active" id="desc">
                    <p>{{ $product->description }}</p>
                </div>

                <!-- THÔNG TIN -->
                <div class="tab-pane fade" id="info">
                    <table class="table">
                        <tbody>

                        @php
                            $fields = [
                                'Mã sản phẩm' => $product->code,
                                'Xuất xứ' => $product->origin,
                                'Chất liệu' => $product->material,
                                'Khối lượng' => $product->weight,
                                'Màu sắc' => $product->colors,
                                'Size' => $product->sizes,
                                'Chất liệu gọng' => $product->frame_material,
                                'Tròng kính' => $product->lens_type,
                                'Chất liệu thân' => $product->upper_material,
                                'Đế giày' => $product->sole_material,
                            ];
                        @endphp

                        @foreach($fields as $label => $value)
                            @if($value)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>{{ $value }}</td>
                                </tr>
                            @endif
                        @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
    <style>
        .main-image img {
            border-radius: 8px;
        }

        .thumbnail-list {
            gap: 10px;
        }

        .thumb-item {
            width: 70px;
            height: 70px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid #eee;
            border-radius: 6px;
            transition: 0.2s;
        }

        .thumb-item:hover {
            border-color: #71cd14;
        }

        .old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 16px;
            margin-left: 10px;
        }
    </style>

    <!--================SCRIPT================-->
    <script>
        function changeImage(el) {
            document.getElementById('mainProductImage').src = el.src;
        }
    </script>

@endsection
