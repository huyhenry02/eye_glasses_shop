@extends('customer.layouts.main')

@section('content')
    <section class="banner_area">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content d-md-flex justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0">
                        <h1>Giỏ hàng</h1>
                        <p>Kiểm tra lại sản phẩm trước khi chuyển sang bước thanh toán.</p>
                    </div>
                    <div class="page_link">
                        <a href="{{ route('customer.showIndex') }}">Trang chủ</a>
                        <a href="{{ route('customer.showCart') }}">Giỏ hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cart_area section_gap">
        <div class="container">
            <div class="cart_inner">
                @if($cartItems->isEmpty())
                    <div class="text-center py-5 border rounded">
                        <h4>Giỏ hàng của bạn đang trống</h4>
                        <p class="mb-4">Hãy chọn thêm sản phẩm để tiếp tục đặt hàng.</p>
                        <a class="main_btn" href="{{ route('customer.showProducts') }}">Tiếp tục mua sắm</a>
                    </div>
                @else
                    <div class="table-responsive border rounded overflow-hidden">
                        <table class="table mb-0 align-middle">
                            <thead>
                            <tr>
                                <th scope="col">Sản phẩm</th>
                                <th scope="col">Đơn giá</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Thành tiền</th>
                                <th scope="col" class="text-right">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cartItems as $item)
                                @php
                                    $product = $item->product;
                                    $price = $product?->final_price ?? 0;
                                    $lineTotal = $price * (int) $item->quantity;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="media align-items-center">
                                            <div class="d-flex mr-3" style="width: 84px;">
                                                <img
                                                    src="{{ $product?->image ?: '/customer/img/product/single-product/cart-1.jpg' }}"
                                                    alt="{{ $product?->name ?? 'Sản phẩm' }}"
                                                    style="width: 72px; height: 72px; object-fit: cover; border-radius: 8px;"
                                                >
                                            </div>
                                            <div class="media-body">
                                                <h5 class="mb-1">{{ $product?->name ?? 'Sản phẩm đã ngừng kinh doanh' }}</h5>
                                                @if(!empty($product?->category?->name))
                                                    <p class="mb-1 text-muted">{{ $product->category->name }}</p>
                                                @endif
                                                @if(!empty($product?->code))
                                                    <small class="text-muted">Mã sản phẩm: {{ $product->code }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>{{ number_format($price, 0, ',', '.') }}đ</h5>
                                    </td>
                                    <td>
                                        <form action="{{ route('customer.cart.update', $item->id) }}" method="POST" class="d-flex align-items-center" style="gap: 8px; min-width: 165px;">
                                            @csrf
                                            <input
                                                type="number"
                                                name="quantity"
                                                min="1"
                                                max="{{ $product?->stock_quantity ?? 1 }}"
                                                value="{{ (int) $item->quantity }}"
                                                class="form-control"
                                                style="max-width: 90px;"
                                            >
                                            <button type="submit" class="genric-btn primary-border small">Cập nhật</button>
                                        </form>
                                    </td>
                                    <td>
                                        <h5>{{ number_format($lineTotal, 0, ',', '.') }}đ</h5>
                                    </td>
                                    <td class="text-right">
                                        <form action="{{ route('customer.cart.delete', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="genric-btn danger-border small">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3" class="text-right font-weight-bold">Tạm tính</td>
                                <td><h5 class="mb-0">{{ number_format($subtotal, 0, ',', '.') }}đ</h5></td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end flex-wrap mt-4" style="gap: 12px;">
                        <a class="gray_btn" href="{{ route('customer.showProducts') }}">Tiếp tục mua sắm</a>
                        <a class="main_btn" href="{{ route('customer.showCheckout') }}">Tiến hành thanh toán</a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
