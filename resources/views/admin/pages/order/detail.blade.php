@extends('admin.layouts.main')

@section('content')
    @php
        $statusClass = match ($order->status) {
            \App\Models\Order::STATUS_PENDING => 'bg-soft-warning text-warning',
            \App\Models\Order::STATUS_PROCESSING => 'bg-soft-primary text-primary',
            \App\Models\Order::STATUS_SHIPPING => 'bg-soft-info text-info',
            \App\Models\Order::STATUS_COMPLETED => 'bg-soft-success text-success',
            \App\Models\Order::STATUS_CANCELLED => 'bg-soft-danger text-danger',
            default => 'bg-soft-secondary text-secondary',
        };

        $paymentStatusClass = match ($order->payment_status) {
            \App\Models\Order::PAYMENT_STATUS_PAID => 'bg-soft-success text-success',
            \App\Models\Order::PAYMENT_STATUS_FAILED => 'bg-soft-danger text-danger',
            \App\Models\Order::PAYMENT_STATUS_UNPAID => 'bg-soft-secondary text-secondary',
            default => 'bg-soft-secondary text-secondary',
        };

        $subtotal = (int) $order->orderDetails->sum('total_price');
    @endphp

    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Chi tiết đơn hàng</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Quản trị</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.order.showIndex') }}">Đơn hàng</a></li>
                <li class="breadcrumb-item">{{ $order->order_code }}</li>
            </ul>
        </div>

        <div class="page-header-right ms-auto">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.order.showEdit', $order->id) }}" class="btn btn-primary">
                    <i class="feather-edit-3 me-2"></i>
                    Cập nhật đơn hàng
                </a>
                <a href="{{ route('admin.order.showIndex') }}" class="btn btn-light">
                    <i class="feather-arrow-left me-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="row g-4 mb-4">
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <span class="fs-12 text-muted d-block mb-2">Mã đơn hàng</span>
                        <h5 class="mb-0">{{ $order->order_code ?? '-' }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <span class="fs-12 text-muted d-block mb-2">Tổng thanh toán</span>
                        <h5 class="mb-0 text-danger">{{ number_format($order->total_amount ?? 0, 0, ',', '.') }}đ</h5>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <span class="fs-12 text-muted d-block mb-2">Trạng thái đơn</span>
                        <span class="badge {{ $statusClass }} fs-12">{{ \App\Models\Order::STATUSES[$order->status] ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <span class="fs-12 text-muted d-block mb-2">Thanh toán</span>
                        <span class="badge {{ $paymentStatusClass }} fs-12">{{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card stretch stretch-full">
                    <div class="card-header">
                        <h6 class="mb-0">Thông tin nhận hàng</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Khách hàng</small>
                            <strong>{{ $order->customer->full_name ?? 'Khách lẻ' }}</strong>
                        </div>
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
                        <div class="mb-0">
                            <small class="text-muted d-block">Địa chỉ giao hàng</small>
                            <span>{{ $order->shipping_address ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="card stretch stretch-full mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">Thông tin thanh toán</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-between gap-3">
                            <span class="text-muted">Phương thức</span>
                            <strong>{{ \App\Models\Order::PAYMENT_METHODS[$order->payment_method] ?? '-' }}</strong>
                        </div>
                        <div class="mb-3 d-flex justify-content-between gap-3">
                            <span class="text-muted">Mã giao dịch</span>
                            <strong class="text-end">{{ $order->payment_transaction_id ?? '-' }}</strong>
                        </div>
                        <div class="mb-3 d-flex justify-content-between gap-3">
                            <span class="text-muted">Ngân hàng</span>
                            <strong>{{ $order->payment_bank_code ?? '-' }}</strong>
                        </div>
                        <div class="mb-3 d-flex justify-content-between gap-3">
                            <span class="text-muted">Mã phản hồi</span>
                            <strong>{{ $order->payment_response_code ?? '-' }}</strong>
                        </div>
                        <div class="mb-3 d-flex justify-content-between gap-3">
                            <span class="text-muted">Thanh toán lúc</span>
                            <strong class="text-end">{{ $order->payment_time?->format('d/m/Y H:i:s') ?? '-' }}</strong>
                        </div>
                        <div class="mb-0 d-flex justify-content-between gap-3">
                            <span class="text-muted">Hoàn thành lúc</span>
                            <strong>{{ $order->completed_at?->format('d/m/Y') ?? '-' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card stretch stretch-full">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Danh sách sản phẩm</h6>
                        <span class="badge bg-soft-primary text-primary">{{ $order->orderDetails->count() }} sản phẩm</span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th style="width: 70px;">#</th>
                                    <th>Sản phẩm</th>
                                    <th style="width: 140px;">Đơn giá</th>
                                    <th style="width: 100px;">SL</th>
                                    <th style="width: 160px;">Thành tiền</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($order->orderDetails as $detail)
                                    @php
                                        $quantity = max((int) $detail->quantity, 1);
                                        $unitPrice = (int) round(((int) $detail->total_price) / $quantity);
                                    @endphp
                                    <tr>
                                        <td class="text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-image rounded" style="width: 56px; height: 56px; overflow: hidden; flex: 0 0 56px;">
                                                    <img
                                                        src="{{ $detail->product?->image ?: '/customer/img/product/inspired-product/i1.jpg' }}"
                                                        alt="{{ $detail->product?->name ?? 'Sản phẩm' }}"
                                                        style="width: 100%; height: 100%; object-fit: cover;"
                                                    >
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark">{{ $detail->product?->name ?? 'Sản phẩm không tồn tại' }}</div>
                                                    <small class="text-muted">Mã SP: {{ $detail->product?->code ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ number_format($unitPrice, 0, ',', '.') }}đ</td>
                                        <td>{{ $quantity }}</td>
                                        <td class="fw-semibold text-dark">{{ number_format($detail->total_price ?? 0, 0, ',', '.') }}đ</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Đơn hàng chưa có sản phẩm nào.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                                @if($order->orderDetails->isNotEmpty())
                                    <tfoot class="table-light">
                                    <tr>
                                        <td colspan="4" class="text-end fw-semibold">Tạm tính</td>
                                        <td class="fw-semibold">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Tổng thanh toán</td>
                                        <td class="fw-bold text-danger">{{ number_format($order->total_amount ?? 0, 0, ',', '.') }}đ</td>
                                    </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
