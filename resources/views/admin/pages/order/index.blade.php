@extends('admin.layouts.main')
@section('content')
    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card stretch stretch-full">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="orderList">
                                <thead class="table-light">
                                <tr>
                                    <th style="width:70px;">STT</th>
                                    <th style="width:250px;">Mã đơn</th>
                                    <th>Người nhận</th>
                                    <th style="width:140px;">Tổng tiền</th>
                                    <th style="width:150px;">Phương thức</th>
                                    <th style="width:150px;">TT thanh toán</th>
                                    <th style="width:150px;">Trạng thái</th>
                                    <th style="width:160px;">Ngày tạo</th>
                                    <th class="text-end" style="width:140px;">Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($orders as $order)
                                    @php
                                        $statusClass = match($order->status) {
                                            'pending' => 'bg-soft-warning text-warning',
                                            'processing' => 'bg-soft-primary text-primary',
                                            'shipping' => 'bg-soft-info text-info',
                                            'completed' => 'bg-soft-success text-success',
                                            'cancelled' => 'bg-soft-danger text-danger',
                                            default => 'bg-soft-secondary text-secondary'
                                        };

                                        $paymentStatusClass = match($order->payment_status) {
                                            'paid' => 'bg-soft-success text-success',
                                            'failed' => 'bg-soft-danger text-danger',
                                            'unpaid' => 'bg-soft-secondary text-secondary',
                                            default => 'bg-soft-secondary text-secondary'
                                        };
                                    @endphp
                                    <tr>
                                        <td class="text-muted">{{ $loop->iteration }}</td>

                                        <td class="fw-semibold text-dark">
                                            {{ $order->order_code ?? '-' }}
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <span
                                                    class="fw-semibold text-dark">{{ $order->shipping_name ?? '-' }}</span>
                                                <small class="text-muted">{{ $order->shipping_phone ?? '-' }}</small>
                                            </div>
                                        </td>

                                        <td class="fw-semibold text-dark">
                                            {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}đ
                                        </td>

                                        <td>
                                            <span class="badge bg-soft-primary text-primary">
                                                {{ \App\Models\Order::PAYMENT_METHODS[$order->payment_method] ?? '-' }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge {{ $paymentStatusClass }}">
                                                {{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? '-' }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge {{ $statusClass }}">
                                                {{ \App\Models\Order::STATUSES[$order->status] ?? '-' }}
                                            </span>
                                        </td>

                                        <td class="text-muted">
                                            {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}
                                        </td>

                                        <td class="text-end">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-light btn-sm" type="button"
                                                        data-bs-toggle="dropdown">
                                                    <i class="feather feather-more-horizontal"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin.order.showDetail', $order->id) }}">
                                                            <i class="feather-eye me-2"></i>
                                                            <span>Xem</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin.order.showEdit', $order->id) }}">
                                                            <i class="feather-edit-3 me-2"></i>
                                                            <span>Sửa</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.order.destroy', $order->id) }}"
                                                              method="POST"
                                                              onsubmit="return confirm('Bạn chắc chắn muốn xóa đơn hàng này?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="feather-trash-2 me-2"></i>
                                                                <span>Xóa</span>
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            Chưa có đơn hàng nào.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($orders, 'links'))
                            <div class="p-3">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #orderList th {
            font-size: 12px;
            font-weight: 600;
        }

        #orderList td {
            font-size: 12px;
        }

        #orderList .badge {
            font-size: 12px;
        }

        #orderList .dropdown-menu .dropdown-item {
            font-size: 12px;
        }

        .table-responsive {
            min-height: calc(100vh - 120px);
        }
    </style>
@endsection
