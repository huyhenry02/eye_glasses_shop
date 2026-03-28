@extends('customer.layouts.main')

@section('content')
    @php
        $statusClass = match ($order->status) {
            \App\Models\Order::STATUS_PENDING => 'secondary',
            \App\Models\Order::STATUS_PROCESSING => 'info',
            \App\Models\Order::STATUS_SHIPPING => 'primary',
            \App\Models\Order::STATUS_COMPLETED => 'success',
            \App\Models\Order::STATUS_CANCELLED => 'danger',
            default => 'secondary',
        };

        $paymentClass = match ($order->payment_status) {
            \App\Models\Order::PAYMENT_STATUS_PAID => 'success',
            \App\Models\Order::PAYMENT_STATUS_FAILED => 'danger',
            default => 'secondary',
        };

        $subtotal = (int) $order->orderDetails->sum('total_price');
    @endphp

    <section class="banner_area">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content d-md-flex justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0">
                        <h1 class="mb-2">Chi tiết đơn hàng</h1>
                        <p class="mb-0">Theo dõi trạng thái và xem lại toàn bộ sản phẩm trong đơn {{ $order->order_code }}</p>
                    </div>
                    <div class="page_link">
                        <a href="{{ route('customer.showIndex') }}">Trang chủ</a>
                        <a href="{{ route('customer.orders.index') }}">Đơn hàng của tôi</a>
                        <a href="#">{{ $order->order_code }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section_gap">
        <div class="container">
            <article class="order-detail-page">
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="single-prd-item border rounded p-4 h-100">
                            <span class="text-muted d-block mb-2">Mã đơn hàng</span>
                            <h4 class="mb-0">{{ $order->order_code }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="single-prd-item border rounded p-4 h-100">
                            <span class="text-muted d-block mb-2">Ngày đặt</span>
                            <h5 class="mb-0">{{ $order->created_at?->format('d/m/Y H:i') ?? '-' }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="single-prd-item border rounded p-4 h-100">
                            <span class="text-muted d-block mb-2">Trạng thái đơn</span>
                            <span class="badge badge-{{ $statusClass }} px-3 py-2">{{ \App\Models\Order::STATUSES[$order->status] ?? $order->status }}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="single-prd-item border rounded p-4 h-100">
                            <span class="text-muted d-block mb-2">Thanh toán</span>
                            <span class="badge badge-{{ $paymentClass }} px-3 py-2">
                                {{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? $order->payment_status }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <section class="border rounded p-4 h-100">
                            <h3 class="mb-4">Thông tin nhận hàng</h3>
                            <div class="mb-3">
                                <small class="text-muted d-block">Người nhận</small>
                                <strong>{{ $order->shipping_name ?? '-' }}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Số điện thoại</small>
                                <span>{{ $order->shipping_phone ?? '-' }}</span>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Email</small>
                                <span>{{ $order->shipping_email ?? '-' }}</span>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Địa chỉ giao hàng</small>
                                <span>{{ $order->shipping_address ?? '-' }}</span>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Phương thức thanh toán</small>
                                <strong>{{ \App\Models\Order::PAYMENT_METHODS[$order->payment_method] ?? $order->payment_method }}</strong>
                            </div>
                            <div class="mb-0">
                                <small class="text-muted d-block">Thời gian thanh toán</small>
                                <span>{{ $order->payment_time?->format('d/m/Y H:i:s') ?? '-' }}</span>
                            </div>
                        </section>
                    </div>

                    <div class="col-lg-8">
                        <section class="border rounded overflow-hidden">
                            <div class="d-flex align-items-center justify-content-between p-4 border-bottom flex-wrap" style="gap: 12px;">
                                <div>
                                    <h2 class="mb-1">Sản phẩm trong đơn</h2>
                                    <p class="mb-0 text-muted">Danh sách sản phẩm đã đặt, số lượng và thành tiền tương ứng.</p>
                                </div>
                                <div class="text-right">
                                    <span class="d-block text-muted">Tổng thanh toán</span>
                                    <strong style="font-size: 22px; color: #71cd14;">{{ number_format($order->total_amount ?? 0, 0, ',', '.') }}đ</strong>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table mb-0 align-middle">
                                    <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th class="text-center">Đơn giá</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-right">Thành tiền</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($order->orderDetails as $detail)
                                        @php
                                            $quantity = max((int) $detail->quantity, 1);
                                            $unitPrice = (int) round(((int) $detail->total_price) / $quantity);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="media align-items-center">
                                                    <div class="d-flex mr-3" style="width: 84px;">
                                                        <img
                                                            src="{{ $detail->product?->image ?: '/customer/img/product/inspired-product/i1.jpg' }}"
                                                            alt="{{ $detail->product?->name ?? 'Sản phẩm kính mắt' }}"
                                                            style="width: 72px; height: 72px; object-fit: cover; border-radius: 8px;"
                                                        >
                                                    </div>
                                                    <div class="media-body">
                                                        <h5 class="mb-1">{{ $detail->product?->name ?? 'Sản phẩm không tồn tại' }}</h5>
                                                        @if(!empty($detail->product?->category?->name))
                                                            <p class="mb-1 text-muted">Danh mục: {{ $detail->product->category->name }}</p>
                                                        @endif
                                                        @if(!empty($detail->product?->code))
                                                            <small class="text-muted">Mã sản phẩm: {{ $detail->product->code }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ number_format($unitPrice, 0, ',', '.') }}đ</td>
                                            <td class="text-center">{{ $quantity }}</td>
                                            <td class="text-right"><strong>{{ number_format($detail->total_price ?? 0, 0, ',', '.') }}đ</strong></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">Không có chi tiết đơn hàng.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                    @if($order->orderDetails->isNotEmpty())
                                        <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-right font-weight-bold">Tạm tính</td>
                                            <td class="text-right">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right font-weight-bold">Phí vận chuyển</td>
                                            <td class="text-right">0đ</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right font-weight-bold">Tổng thanh toán</td>
                                            <td class="text-right"><strong>{{ number_format($order->total_amount ?? 0, 0, ',', '.') }}đ</strong></td>
                                        </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </section>

                        <div class="mt-4 d-flex flex-wrap" style="gap: 12px;">
                            <a href="{{ route('customer.orders.index') }}" class="genric-btn primary-border">Quay lại đơn hàng</a>
                            <a href="{{ route('customer.showProducts') }}" class="genric-btn success">Tiếp tục mua sắm</a>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </section>
@endsection
