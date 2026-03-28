@extends('admin.layouts.main')

@section('content')
    @php
        $shippingName = old('shipping_name', $order->shipping_name ?? '');
        $shippingPhone = old('shipping_phone', $order->shipping_phone ?? '');
        $shippingEmail = old('shipping_email', $order->shipping_email ?? '');
        $shippingAddress = old('shipping_address', $order->shipping_address ?? '');
        $status = old('status', $order->status ?? \App\Models\Order::STATUS_PENDING);
        $paymentStatus = old('payment_status', $order->payment_status ?? \App\Models\Order::PAYMENT_STATUS_UNPAID);
        $paymentMethod = old('payment_method', $order->payment_method ?? \App\Models\Order::PAYMENT_METHOD_COD);
    @endphp
    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-top-0">
                    <div class="card-body">
                        <form action="{{ route('admin.order.update', $order->id) }}" method="POST">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-lg-12">
                                    <h6 class="fw-bold mb-3">Thông tin đơn hàng</h6>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Mã đơn hàng</label>
                                    <input type="text" class="form-control" value="{{ $order->order_code }}" disabled>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Tổng tiền</label>
                                    <input type="text" class="form-control" value="{{ number_format($order->total_amount ?? 0, 0, ',', '.') }}đ" disabled>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Người nhận</label>
                                    <input type="text" name="shipping_name" class="form-control" value="{{ $shippingName }}" required>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Số điện thoại</label>
                                    <input type="text" name="shipping_phone" class="form-control" value="{{ $shippingPhone }}" required>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" name="shipping_email" class="form-control" value="{{ $shippingEmail }}">
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Phương thức thanh toán</label>
                                    <select name="payment_method" class="form-select" required>
                                        @foreach(\App\Models\Order::PAYMENT_METHODS as $key => $label)
                                            <option value="{{ $key }}" {{ $paymentMethod === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Trạng thái thanh toán</label>
                                    <select name="payment_status" class="form-select" required>
                                        @foreach(\App\Models\Order::PAYMENT_STATUSES as $key => $label)
                                            <option value="{{ $key }}" {{ $paymentStatus === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Trạng thái đơn hàng</label>
                                    <select name="status" class="form-select" required>
                                        @foreach(\App\Models\Order::STATUSES as $key => $label)
                                            <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-12 mb-4">
                                    <label class="form-label fw-semibold">Địa chỉ nhận hàng</label>
                                    <textarea name="shipping_address" class="form-control" rows="3" required>{{ $shippingAddress }}</textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.order.showIndex') }}" class="btn btn-light">
                                    <i class="feather-arrow-left me-2"></i>
                                    Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="feather-save me-2"></i>
                                    Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
