@extends('customer.layouts.main')
@section('content')
    <!-- breadcrumb -->
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ route('customer.showIndex') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Trang chủ
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <a href="{{ route('customer.showProducts') }}" class="stext-109 cl8 hov-cl1 trans-04">
                {{ $product->category->name ?? '' }}
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
				{{ $product->name ?? '' }}
			</span>
        </div>
    </div>

    <section class="sec-product-detail bg0 p-t-65 p-b-60">
        <div class="container">

            @if(session('success'))
                <div class="alert alert-success m-b-20">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger m-b-20">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6 col-lg-7 p-b-30">
                    <div class="p-l-25 p-r-30 p-lr-0-lg">
                        <div class="wrap-slick3 flex-sb flex-w">
                            <div class="wrap-slick3-dots"></div>
                            <div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

                            <div class="slick3 gallery-lb">
                                @if(!empty($product->image_detail_1))
                                    <div class="item-slick3" data-thumb="{{ $product->image_detail_1 }}">
                                        <div class="wrap-pic-w pos-relative">
                                            <img src="{{ $product->image_detail_1 }}" alt="IMG-PRODUCT">

                                            <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
                                               href="{{ $product->image_detail_1 }}">
                                                <i class="fa fa-expand"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($product->image_detail_2))
                                    <div class="item-slick3" data-thumb="{{ $product->image_detail_2 }}">
                                        <div class="wrap-pic-w pos-relative">
                                            <img src="{{ $product->image_detail_2 }}" alt="IMG-PRODUCT">

                                            <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
                                               href="{{ $product->image_detail_2 }}">
                                                <i class="fa fa-expand"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($product->image_detail_3))
                                    <div class="item-slick3" data-thumb="{{ $product->image_detail_3 }}">
                                        <div class="wrap-pic-w pos-relative">
                                            <img src="{{ $product->image_detail_3 }}" alt="IMG-PRODUCT">

                                            <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
                                               href="{{ $product->image_detail_3 }}">
                                                <i class="fa fa-expand"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if(empty($product->image_detail_1) && empty($product->image_detail_2) && empty($product->image_detail_3))
                                    <div class="item-slick3" data-thumb="/customer/images/product-detail-01.jpg">
                                        <div class="wrap-pic-w pos-relative">
                                            <img src="/customer/images/product-detail-01.jpg" alt="IMG-PRODUCT">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-5 p-b-30">
                    <div class="p-r-50 p-t-5 p-lr-0-lg">
                        <h4 class="mtext-105 cl2 js-name-detail p-b-14">
                            {{ $product->name ?? '' }}
                        </h4>

                        @php
                            $finalPrice = (!empty($product->discount_price) && (int)$product->discount_price > 0)
                                ? (int)$product->discount_price
                                : (int)($product->price ?? 0);
                        @endphp

                        <span class="mtext-106 cl2">
							{{ number_format($finalPrice, 0, ',', '.') }} VND
						</span>

                        <p class="stext-102 cl3 p-t-23">
                            {{ $product->description ?? '' }}
                        </p>

                        <form action="{{ route('customer.cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="p-t-33">
                                <div class="flex-w flex-r-m p-b-10">
                                    <div class="size-203 flex-c-m respon6">
                                        Size
                                    </div>

                                    <div class="size-204 respon6-next">
                                        <div class="rs1-select2 bor8 bg0">
                                            <select class="js-select2" name="size" required>
                                                <option value="">Chọn size</option>
                                                @foreach($product->sizeArray as $size)
                                                    <option
                                                        value="{{ $size }}" {{ old('size') == $size ? 'selected' : '' }}>
                                                        {{ $size }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                        @error('size')
                                        <small class="text-danger d-block m-t-5">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex-w flex-r-m p-b-10">
                                    <div class="size-203 flex-c-m respon6">
                                        Màu
                                    </div>

                                    <div class="size-204 respon6-next">
                                        <div class="rs1-select2 bor8 bg0">
                                            <select class="js-select2" name="color" required>
                                                <option value="">Chọn màu</option>
                                                @foreach($product->colorArray as $color)
                                                    <option
                                                        value="{{ $color }}" {{ old('color') == $color ? 'selected' : '' }}>
                                                        {{ $color }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                        @error('color')
                                        <small class="text-danger d-block m-t-5">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex-w flex-r-m p-b-10">
                                    <div class="size-204 flex-w flex-m respon6-next">
                                        <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                                            <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                                <i class="fs-16 zmdi zmdi-minus"></i>
                                            </div>

                                            <input class="mtext-104 cl3 txt-center num-product"
                                                   type="number"
                                                   name="quantity"
                                                   min="1"
                                                   value="{{ old('quantity', 1) }}">

                                            <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                                <i class="fs-16 zmdi zmdi-plus"></i>
                                            </div>
                                        </div>

                                        <button type="submit"
                                                class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                            Thêm vào giỏ hàng
                                        </button>
                                    </div>
                                </div>

                                @error('quantity')
                                <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bor10 m-t-50 p-t-43 p-b-40">
                <div class="tab01">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item p-b-10">
                            <a class="nav-link active" data-toggle="tab" href="#description" role="tab">
                                Mô tả
                            </a>
                        </li>

                        <li class="nav-item p-b-10">
                            <a class="nav-link" data-toggle="tab" href="#information" role="tab">
                                Thông tin chi tiết
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content p-t-43">
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <div class="how-pos2 p-lr-15-md">
                                <p class="stext-102 cl6">
                                    {{ $product->description ?? '' }}
                                </p>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="information" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
                                    <ul class="p-lr-28 p-lr-15-sm">
                                        <li class="flex-w flex-t p-b-7">
											<span class="stext-102 cl3 size-205">
												Cân nặng
											</span>

                                            <span class="stext-102 cl6 size-206">
												{{ $product->weight ?? '' }}
											</span>
                                        </li>

                                        <li class="flex-w flex-t p-b-7">
											<span class="stext-102 cl3 size-205">
												Kích thước
											</span>

                                            <span class="stext-102 cl6 size-206">
												{{ $product->dimension ?? '' }}
											</span>
                                        </li>

                                        <li class="flex-w flex-t p-b-7">
											<span class="stext-102 cl3 size-205">
												Chất liệu
											</span>

                                            <span class="stext-102 cl6 size-206">
												{{ $product->material ?? '' }}
											</span>
                                        </li>

                                        <li class="flex-w flex-t p-b-7">
											<span class="stext-102 cl3 size-205">
												Màu sắc
											</span>

                                            <span class="stext-102 cl6 size-206">
												{{ $product->colors ?? '' }}
											</span>
                                        </li>

                                        <li class="flex-w flex-t p-b-7">
											<span class="stext-102 cl3 size-205">
												Size
											</span>

                                            <span class="stext-102 cl6 size-206">
												{{ $product->category->sizes ?? '' }}
											</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg6 flex-c-m flex-w size-302 m-t-73 p-tb-15">
			<span class="stext-107 cl6 p-lr-25">
				Mã sản phẩm: {{ $product->code ?? ''}}
			</span>
            <span class="stext-107 cl6 p-lr-25">
				Danh mục sản phẩm: {{ $product->category->name ?? '' }}
			</span>
        </div>
    </section>

    <!-- Related Products -->
    <section class="sec-relate-product bg0 p-t-45 p-b-105">
        <div class="container">
            <div class="p-b-45">
                <h3 class="ltext-106 cl5 txt-center">
                    Sản phẩm liên quan
                </h3>
            </div>

            <div class="wrap-slick2">
                <div class="slick2">
                    @foreach($productsFeatured as $item)
                        <div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
                            <div class="block2">
                                <div class="block2-pic hov-img0">
                                    <img src="{{ $item->image ?? '' }}" alt="IMG-PRODUCT">

                                    <a href="{{ route('customer.showProductDetail', $item->id) }}"
                                       class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">
                                        Xem nhanh
                                    </a>
                                </div>

                                <div class="block2-txt flex-w flex-t p-t-14">
                                    <div class="block2-txt-child1 flex-col-l ">
                                        <a href="{{ route('customer.showProductDetail', $item->id) }}"
                                           class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                            {{ $item->name ?? '' }}
                                        </a>

                                        <span class="stext-105 cl3">
										{{ number_format($item->discount_price, 0, ',', '.') }} VND
									</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
