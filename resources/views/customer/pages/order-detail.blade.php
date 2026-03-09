@extends('customer.layouts.main')
@section('content')
    <div class="container p-t-50 p-b-50">
        <h3 class="mtext-111 cl2 p-b-25">Chi tiết đơn hàng</h3>

        <div class="m-b-30">
            <p><strong>Mã đơn hàng:</strong> {{ $order->order_code }}</p>
            <p><strong>Người nhận:</strong> {{ $order->shipping_name }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->shipping_phone }}</p>
            <p><strong>Email:</strong> {{ $order->shipping_email }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>

            <p>
                <strong>Trạng thái đơn:</strong>
                {{ \App\Models\Order::STATUSES[$order->status] ?? '—' }}
            </p>

            <p>
                <strong>Trạng thái thanh toán:</strong>
                {{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? '—' }}
            </p>

            <p>
                <strong>Phương thức thanh toán:</strong>
                {{ \App\Models\Order::PAYMENT_METHODS[$order->payment_method] ?? '—' }}
            </p>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Size</th>
                    <th>Màu</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->orderDetails as $detail)
                    <tr>
                        <td>{{ $detail->product->name ?? 'Sản phẩm' }}</td>
                        <td>{{ $detail->size ?? '—' }}</td>
                        <td>{{ $detail->color ?? '—' }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->total_price, 0, ',', '.') }} đ</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-right">
            <h5>
                <strong>Tổng cộng: {{ number_format($order->total_amount, 0, ',', '.') }} đ</strong>
            </h5>
        </div>
    </div>
@endsection
