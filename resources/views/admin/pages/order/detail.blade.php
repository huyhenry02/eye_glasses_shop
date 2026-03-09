@extends('admin.layouts.main')

@section('content')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Chi tiết đơn hàng</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Quản trị</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.order.showIndex') }}">Đơn hàng</a></li>
                <li class="breadcrumb-item">Chi tiết</li>
            </ul>
        </div>

        <div class="page-header-right ms-auto">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.order.showEdit', $order->id) }}" class="btn btn-primary">
                    <i class="feather-edit-3 me-2"></i>
                    Sửa đơn hàng
                </a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Thông tin đơn hàng</h6>

                        <div class="mb-2"><strong>Mã đơn:</strong> {{ $order->order_code ?? '—' }}</div>
                        <div class="mb-2"><strong>Khách hàng:</strong> {{ $order->customer->full_name ?? 'Khách lẻ' }}</div>
                        <div class="mb-2"><strong>Người nhận:</strong> {{ $order->shipping_name ?? '—' }}</div>
                        <div class="mb-2"><strong>Số điện thoại:</strong> {{ $order->shipping_phone ?? '—' }}</div>
                        <div class="mb-2"><strong>Email:</strong> {{ $order->shipping_email ?? '—' }}</div>
                        <div class="mb-2"><strong>Địa chỉ:</strong> {{ $order->shipping_address ?? '—' }}</div>
                        <div class="mb-2"><strong>Phương thức thanh toán:</strong> {{ \App\Models\Order::PAYMENT_METHODS[$order->payment_method] ?? '—' }}</div>
                        <div class="mb-2"><strong>Trạng thái thanh toán:</strong> {{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? '—' }}</div>
                        <div class="mb-2"><strong>Trạng thái đơn:</strong> {{ \App\Models\Order::STATUSES[$order->status] ?? '—' }}</div>
                        <div class="mb-2"><strong>Mã giao dịch:</strong> {{ $order->payment_transaction_id ?? '—' }}</div>
                        <div class="mb-2"><strong>Ngân hàng:</strong> {{ $order->payment_bank_code ?? '—' }}</div>
                        <div class="mb-2"><strong>Mã phản hồi:</strong> {{ $order->payment_response_code ?? '—' }}</div>
                        <div class="mb-2"><strong>Thời gian thanh toán:</strong> {{ $order->payment_time ? \Carbon\Carbon::parse($order->payment_time)->format('d/m/Y H:i:s') : '—' }}</div>
                        <div class="mb-2"><strong>Ngày hoàn thành:</strong> {{ $order->completed_at ? \Carbon\Carbon::parse($order->completed_at)->format('d/m/Y') : '—' }}</div>
                        <div class="mb-0"><strong>Tổng tiền:</strong> <span class="fw-bold text-danger">{{ number_format($order->total_amount ?? 0, 0, ',', '.') }}đ</span></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Chi tiết sản phẩm</h6>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Sản phẩm</th>
                                    <th>Size</th>
                                    <th>Màu</th>
                                    <th>SL</th>
                                    <th>Thành tiền</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($order->orderDetails as $detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $detail->product->name ?? '—' }}</td>
                                        <td>{{ $detail->size ?? '—' }}</td>
                                        <td>{{ $detail->color ?? '—' }}</td>
                                        <td>{{ $detail->quantity ?? 0 }}</td>
                                        <td>{{ number_format($detail->total_price ?? 0, 0, ',', '.') }}đ</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Không có sản phẩm nào.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.order.showIndex') }}" class="btn btn-light">
                                Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
