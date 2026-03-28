@extends('admin.layouts.main')

@section('content')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Danh sách hóa đơn</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Quản trị</a></li>
                <li class="breadcrumb-item">Hóa đơn</li>
            </ul>
        </div>

        <div class="page-header-right ms-auto">
            <a href="{{ route('admin.invoice.showCreate') }}" class="btn btn-primary">
                <i class="feather-plus me-2"></i>
                Tạo hóa đơn
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card stretch stretch-full">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="invoiceListTable">
                                <thead class="table-light">
                                <tr>
                                    <th style="width:70px;">STT</th>
                                    <th style="width:150px;">Mã hóa đơn</th>
                                    <th>Khách hàng</th>
                                    <th style="width:110px;">Sản phẩm</th>
                                    <th style="width:150px;">Tổng tiền</th>
                                    <th style="width:150px;">Phương thức</th>
                                    <th style="width:170px;">TT thanh toán</th>
                                    <th style="width:150px;">Trạng thái</th>
                                    <th style="width:150px;">Nhân viên</th>
                                    <th style="width:160px;">Ngày tạo</th>
                                    <th class="text-end" style="width:140px;">Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($invoices as $invoice)
                                    @php
                                        $statusClass = match($invoice->status) {
                                            \App\Models\Invoice::STATUS_DRAFT => 'bg-soft-secondary text-secondary',
                                            \App\Models\Invoice::STATUS_COMPLETED => 'bg-soft-success text-success',
                                            \App\Models\Invoice::STATUS_CANCELLED => 'bg-soft-danger text-danger',
                                            default => 'bg-soft-secondary text-secondary'
                                        };

                                        $paymentStatusClass = match($invoice->payment_status) {
                                            \App\Models\Invoice::PAYMENT_STATUS_PAID => 'bg-soft-success text-success',
                                            \App\Models\Invoice::PAYMENT_STATUS_FAILED => 'bg-soft-danger text-danger',
                                            \App\Models\Invoice::PAYMENT_STATUS_UNPAID => 'bg-soft-warning text-warning',
                                            default => 'bg-soft-secondary text-secondary'
                                        };
                                    @endphp
                                    <tr>
                                        <td class="text-muted">{{ $loop->iteration }}</td>
                                        <td class="fw-semibold text-dark">{{ $invoice->invoice_code ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold text-dark">{{ $invoice->customer_name ?: 'Khách lẻ' }}</span>
                                                <small class="text-muted">{{ $invoice->customer_phone ?: '-' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-primary text-primary">
                                                {{ (int) ($invoice->invoice_details_count ?? 0) }} sản phẩm
                                            </span>
                                        </td>
                                        <td class="fw-semibold text-dark">{{ number_format($invoice->total_amount ?? 0, 0, ',', '.') }}đ</td>
                                        <td>
                                            <span class="badge bg-soft-info text-info">
                                                {{ \App\Models\Invoice::PAYMENT_METHODS[$invoice->payment_method] ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $paymentStatusClass }}">
                                                {{ \App\Models\Invoice::PAYMENT_STATUSES[$invoice->payment_status] ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $statusClass }}">
                                                {{ \App\Models\Invoice::STATUSES[$invoice->status] ?? '-' }}
                                            </span>
                                        </td>
                                        <td>{{ $invoice->employee->full_name ?? '-' }}</td>
                                        <td class="text-muted">{{ $invoice->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                        <td class="text-end">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                                    <i class="feather-more-horizontal"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.invoice.showEdit', $invoice->id) }}">
                                                            <i class="feather-edit-3 me-2"></i>
                                                            <span>Sửa</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.invoice.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa hóa đơn này?')">
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
                                        <td colspan="11" class="text-center py-5 text-muted">Chưa có hóa đơn nào.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($invoices, 'links'))
                            <div class="p-3">
                                {{ $invoices->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #invoiceListTable th,
        #invoiceListTable td,
        #invoiceListTable .badge,
        #invoiceListTable .dropdown-menu .dropdown-item {
            font-size: 12px;
        }
        .table-responsive {
            min-height: calc(100vh - 120px);
        }
    </style>
@endsection
