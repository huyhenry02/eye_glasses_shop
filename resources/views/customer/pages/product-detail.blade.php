@extends('customer.layouts.main')

@section('content')
    <section class="banner_area">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content d-md-flex justify-content-between align-items-center">
                    <div>
                        <h1>Chi tiết sản phẩm</h1>
                        <p class="mb-0">Thông tin đầy đủ về mẫu kính bạn đang quan tâm.</p>
                    </div>
                    <div class="page_link">
                        <a href="{{ route('customer.showIndex') }}">Trang chủ</a>
                        <a href="{{ route('customer.showProducts') }}">Sản phẩm</a>
                        <a href="#">{{ $product->name }}</a>
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
            $product->image_detail_3,
        ])->filter();

        $price = $product->discount_price ?: $product->price;

        $fields = [
            'Mã sản phẩm' => $product->code,
            'Danh mục' => $product->category?->name,
            'Thương hiệu' => $product->brand,
            'Chất liệu gọng' => $product->frame_material,
            'Chất liệu tròng' => $product->lens_material,
            'Kiểu dáng gọng' => $product->shape,
            'Kiểu viền' => $product->rim_type,
            'Giới tính' => $product->gender,
            'Màu gọng' => $product->frame_color,
            'Màu tròng' => $product->lens_color,
            'Màu sắc khác' => $product->colors,
            'Độ rộng tròng' => !empty($product->lens_width) ? $product->lens_width . ' mm' : null,
            'Cầu kính' => !empty($product->bridge_width) ? $product->bridge_width . ' mm' : null,
            'Chiều dài càng kính' => !empty($product->temple_length) ? $product->temple_length . ' mm' : null,
            'Bề ngang gọng' => !empty($product->frame_width) ? $product->frame_width . ' mm' : null,
            'Tình trạng kho' => (int) $product->stock_quantity > 0 ? 'Còn hàng' : 'Hết hàng',
        ];
    @endphp

    <div class="product_image_area section_gap_top">
        <div class="container">
            <div class="row s_product_inner">
                <div class="col-lg-6">
                    <div class="main-image">
                        <img id="mainProductImage" src="{{ $images->first() ?: '/customer/img/product/inspired-product/i1.jpg' }}" class="img-fluid w-100" alt="{{ $product->name }}">
                    </div>

                    @if($images->count() > 1)
                        <div class="thumbnail-list mt-3 d-flex flex-wrap">
                            @foreach($images as $img)
                                <img src="{{ $img }}" class="thumb-item" alt="{{ $product->name }}" onclick="changeImage(this)">
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-lg-5 offset-lg-1">
                    <div class="s_product_text">
                        <h2 class="product_title mb-3">{{ $product->name }}</h2>

                        <h3 class="mb-3">
                            {{ number_format($price ?? 0, 0, ',', '.') }}đ
                            @if(!empty($product->discount_price) && (int) $product->discount_price < (int) $product->price)
                                <span class="old-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                            @endif
                        </h3>

                        <ul class="list">
                            @if($product->category)
                                <li><span>Danh mục:</span> {{ $product->category->name }}</li>
                            @endif
                            @if($product->brand)
                                <li><span>Thương hiệu:</span> {{ $product->brand }}</li>
                            @endif
                            <li><span>Tình trạng:</span> {{ (int) $product->stock_quantity > 0 ? 'Còn hàng' : 'Hết hàng' }}</li>
                        </ul>

                        @if(!empty($product->description))
                            <p>{{ $product->description }}</p>
                        @endif

                        <div class="card_area d-flex align-items-center" style="gap: 10px;">
                            <form action="{{ route('customer.cart.add') }}" method="POST" class="d-flex align-items-center" style="gap: 10px; flex-wrap: wrap;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input
                                    type="number"
                                    name="quantity"
                                    min="1"
                                    max="{{ max((int) $product->stock_quantity, 1) }}"
                                    value="1"
                                    class="form-control"
                                    style="width: 90px;"
                                    {{ (int) $product->stock_quantity <= 0 ? 'disabled' : '' }}
                                >
                                <button type="submit" class="main_btn border-0" {{ (int) $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                    Thêm vào giỏ hàng
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="product_description_area section_gap">
        <div class="container">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#desc">Mô tả</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#info">Thông tin chi tiết</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="desc">
                    <p>{{ $product->description ?: 'Sản phẩm hiện chưa có mô tả chi tiết.' }}</p>
                </div>

                <div class="tab-pane fade" id="info">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            @foreach($fields as $label => $value)
                                @if(!empty($value))
                                    <tr>
                                        <td style="width: 35%;">{{ $label }}</td>
                                        <td>{{ $value }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
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

    <script>
        function changeImage(el) {
            document.getElementById('mainProductImage').src = el.src;
        }
    </script>
@endsection
