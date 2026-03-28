@extends('customer.layouts.main')

@section('content')
    @php
        $statusBadgeMap = [
            \App\Models\Order::STATUS_PENDING => 'secondary',
            \App\Models\Order::STATUS_PROCESSING => 'info',
            \App\Models\Order::STATUS_SHIPPING => 'primary',
            \App\Models\Order::STATUS_COMPLETED => 'success',
            \App\Models\Order::STATUS_CANCELLED => 'danger',
        ];

        $paymentBadgeMap = [
            \App\Models\Order::PAYMENT_STATUS_UNPAID => 'secondary',
            \App\Models\Order::PAYMENT_STATUS_PAID => 'success',
            \App\Models\Order::PAYMENT_STATUS_FAILED => 'danger',
        ];
    @endphp

    <section class="banner_area">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content d-md-flex justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0">
                        <h1 class="mb-2">Đơn hàng của tôi</h1>
                        <p class="mb-0">Theo dõi tình trạng giao hàng và lịch sử mua sắm của bạn.</p>
                    </div>
                    <div class="page_link">
                        <a href="{{ route('customer.showIndex') }}">Trang chủ</a>
                        <a href="{{ route('customer.orders.index') }}">Đơn hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section_gap">
        <div class="container">
            @if($orders->isEmpty())
                <div class="text-center py-5 border rounded">
                    <h4>Bạn chưa có đơn hàng nào</h4>
                    <p class="mb-4">Hãy khám phá các mẫu kính nổi bật và đặt hàng ngay hôm nay.</p>
                    <a href="{{ route('customer.showProducts') }}" class="main_btn">Mua sắm ngay</a>
                </div>
            @else
                <div class="table-responsive border rounded overflow-hidden">
                    <table class="table mb-0 align-middle">
                        <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Số sản phẩm</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <strong>{{ $order->order_code }}</strong>
                                </td>
                                <td>{{ $order->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $order->order_details_count ?? 0 }}</td>
                                <td><strong>{{ number_format($order->total_amount ?? 0, 0, ',', '.') }}đ</strong></td>
                                <td>
                                    <div class="mb-1">{{ \App\Models\Order::PAYMENT_METHODS[$order->payment_method] ?? $order->payment_method }}</div>
                                    <span class="badge badge-{{ $paymentBadgeMap[$order->payment_status] ?? 'secondary' }}">
                                        {{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? $order->payment_status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $statusBadgeMap[$order->status] ?? 'secondary' }}">
                                        {{ \App\Models\Order::STATUSES[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="genric-btn primary-border small">Xem chi tiết</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
