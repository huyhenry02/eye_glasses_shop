@extends('customer.layouts.main')
@section('content')
    <div class="container p-t-50 p-b-50">
        <h3 class="mtext-111 cl2 p-b-25">Đơn hàng của tôi</h3>

        @if(session('success'))
            <div class="alert alert-success m-b-20">
                {{ session('success') }}
            </div>
        @endif

        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái đơn</th>
                        <th>Thanh toán</th>
                        <th>Phương thức</th>
                        <th>Ngày tạo</th>
                        <th>Xem</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_code }}</td>
                            <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                            <td>{{ \App\Models\Order::STATUSES[$order->status] ?? '—' }}</td>
                            <td>{{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? '—' }}</td>
                            <td>{{ \App\Models\Order::PAYMENT_METHODS[$order->payment_method] ?? '—' }}</td>
                            <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</td>
                            <td>
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                    Chi tiết
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>Bạn chưa có đơn hàng nào.</p>
        @endif
    </div>
@endsection
