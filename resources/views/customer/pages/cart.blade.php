@extends('customer.layouts.main')
@section('content')
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ route('customer.showIndex') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Trang chủ
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                Giỏ hàng
            </span>
        </div>
    </div>

    <div class="bg0 p-t-75 p-b-85">
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
                <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                    <div class="m-l-25 m-r--38 m-lr-0-xl">
                        <div class="wrap-table-shopping-cart">
                            <table class="table-shopping-cart">
                                <tr class="table_head">
                                    <th class="column-1">Sản phẩm</th>
                                    <th class="column-2">Thông tin</th>
                                    <th class="column-3">Đơn giá</th>
                                    <th class="column-4">Số lượng</th>
                                    <th class="column-5">Thành tiền</th>
                                    <th class="column-5">Xóa</th>
                                </tr>

                                @forelse($cartItems as $item)
                                    @php
                                        $price = !empty($item->product->discount_price) && (int)$item->product->discount_price > 0
                                            ? (int)$item->product->discount_price
                                            : (int)($item->product->price ?? 0);

                                        $lineTotal = $price * (int)$item->quantity;
                                    @endphp

                                    <tr class="table_row">
                                        <td class="column-1">
                                            <div class="how-itemcart1">
                                                <img src="{{ $item->product->image ?? $item->product->image_detail_1 ?? '/customer/images/item-cart-01.jpg' }}" alt="IMG">
                                            </div>
                                        </td>

                                        <td class="column-2">
                                            <div><strong>{{ $item->product->name ?? 'Sản phẩm' }}</strong></div>
                                            <div>Size: {{ $item->size ?? '---' }}</div>
                                            <div>Màu: {{ $item->color ?? '---' }}</div>
                                        </td>

                                        <td class="column-3">
                                            {{ number_format($price, 0, ',', '.') }} đ
                                        </td>

                                        <td class="column-4">
                                            <form action="{{ route('customer.cart.update', $item->id) }}" method="POST">
                                                @csrf
                                                <div class="d-flex" style="gap:8px; align-items:center;">
                                                    <input class="form-control"
                                                           type="number"
                                                           name="quantity"
                                                           min="1"
                                                           value="{{ $item->quantity }}"
                                                           style="width:90px;">

                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        Cập nhật
                                                    </button>
                                                </div>
                                            </form>
                                        </td>

                                        <td class="column-5">
                                            {{ number_format($lineTotal, 0, ',', '.') }} đ
                                        </td>

                                        <td class="column-5">
                                            <form action="{{ route('customer.cart.delete', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Xóa
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center p-4">
                                            Giỏ hàng của bạn đang trống.
                                        </td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                    <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                        <h4 class="mtext-109 cl2 p-b-30">
                            Tổng giỏ hàng
                        </h4>

                        <div class="flex-w flex-t bor12 p-b-13">
                            <div class="size-208">
                                <span class="stext-110 cl2">Tạm tính:</span>
                            </div>

                            <div class="size-209">
                                <span class="mtext-110 cl2">
                                    {{ number_format($subtotal, 0, ',', '.') }} đ
                                </span>
                            </div>
                        </div>

                        <div class="flex-w flex-t p-t-27 p-b-33">
                            <div class="size-208">
                                <span class="mtext-101 cl2">Tổng cộng:</span>
                            </div>

                            <div class="size-209 p-t-1">
                                <span class="mtext-110 cl2">
                                    {{ number_format($subtotal, 0, ',', '.') }} đ
                                </span>
                            </div>
                        </div>

                        @if($cartItems->count() > 0)
                            <a href="{{ route('customer.showCheckout') }}"
                               class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
                                Tiến hành thanh toán
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
