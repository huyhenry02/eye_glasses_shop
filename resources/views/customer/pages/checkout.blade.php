@extends('customer.layouts.main')

@section('content')
    <section class="banner_area">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content d-md-flex justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0">
                        <h1>Thanh toán</h1>
                        <p>Hoàn tất thông tin nhận hàng để tạo đơn hàng nhanh chóng và chính xác.</p>
                    </div>
                    <div class="page_link">
                        <a href="{{ route('customer.showIndex') }}">Trang chủ</a>
                        <a href="{{ route('customer.showCheckout') }}">Thanh toán</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="checkout_area section_gap">
        <div class="container">
            <div class="billing_details">
                <form class="row" action="{{ route('customer.storeOrder') }}" method="POST">
                    @csrf
                    <div class="col-lg-8">
                        <h3 class="mb-4">Thông tin nhận hàng</h3>
                        <div class="row contact_form">
                            <div class="col-md-6 form-group p_star">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="shipping_name"
                                    name="shipping_name"
                                    value="{{ old('shipping_name', $customer->full_name ?? '') }}"
                                    required
                                >
                                <span class="placeholder" data-placeholder="Họ và tên"></span>
                            </div>

                            <div class="col-md-6 form-group p_star">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="shipping_phone"
                                    name="shipping_phone"
                                    value="{{ old('shipping_phone', auth()->user()->phone ?? '') }}"
                                    required
                                >
                                <span class="placeholder" data-placeholder="Số điện thoại"></span>
                            </div>

                            <div class="col-md-12 form-group p_star">
                                <input
                                    type="email"
                                    class="form-control"
                                    id="shipping_email"
                                    name="shipping_email"
                                    value="{{ old('shipping_email', $customer->email ?? '') }}"
                                >
                                <span class="placeholder" data-placeholder="Email"></span>
                            </div>

                            <div class="col-md-12 form-group p_star">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="shipping_address"
                                    name="shipping_address"
                                    value="{{ old('shipping_address', $customer->address ?? '') }}"
                                    required
                                >
                                <span class="placeholder" data-placeholder="Địa chỉ nhận hàng"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="order_box">
                            <h2>Đơn hàng của bạn</h2>
                            <ul class="list">
                                <li>
                                    <a href="javascript:void(0)">Sản phẩm <span>Tổng</span></a>
                                </li>
                                @foreach($cartItems as $item)
                                    @php
                                        $price = $item->product?->final_price ?? 0;
                                        $lineTotal = $price * (int) $item->quantity;
                                    @endphp
                                    <li>
                                        <a href="javascript:void(0)">
                                            {{ $item->product?->name ?? 'Sản phẩm' }}
                                            <span class="middle">x {{ (int) $item->quantity }}</span>
                                            <span class="last">{{ number_format($lineTotal, 0, ',', '.') }}đ</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <ul class="list list_2">
                                <li>
                                    <a href="javascript:void(0)">Tạm tính <span>{{ number_format($subtotal, 0, ',', '.') }}đ</span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">Phí vận chuyển <span>0đ</span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">Tổng thanh toán <span>{{ number_format($subtotal, 0, ',', '.') }}đ</span></a>
                                </li>
                            </ul>

                            <div class="payment_item">
                                <div class="radion_btn">
                                    <input
                                        type="radio"
                                        id="payment_cod"
                                        name="payment_method"
                                        value="{{ \App\Models\Order::PAYMENT_METHOD_COD }}"
                                        {{ old('payment_method', \App\Models\Order::PAYMENT_METHOD_COD) === \App\Models\Order::PAYMENT_METHOD_COD ? 'checked' : '' }}
                                    >
                                    <label for="payment_cod">Thanh toán khi nhận hàng (COD)</label>
                                    <div class="check"></div>
                                </div>
                                <p>Bạn thanh toán bằng tiền mặt khi nhận hàng tại địa chỉ đã đăng ký.</p>
                            </div>

                            <div class="payment_item">
                                <div class="radion_btn">
                                    <input
                                        type="radio"
                                        id="payment_vnpay"
                                        name="payment_method"
                                        value="{{ \App\Models\Order::PAYMENT_METHOD_VNPAY }}"
                                        {{ old('payment_method') === \App\Models\Order::PAYMENT_METHOD_VNPAY ? 'checked' : '' }}
                                    >
                                    <label for="payment_vnpay">Thanh toán qua VNPay</label>
                                    <div class="check"></div>
                                </div>
                                <p>Bạn sẽ được chuyển sang cổng thanh toán VNPay để hoàn tất giao dịch một cách an toàn.</p>
                            </div>

                            <button type="submit" class="main_btn w-100 border-0">Đặt hàng ngay</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
