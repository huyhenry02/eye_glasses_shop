@extends('admin.layouts.main')

@section('content')
    <div class="page-header">
        <div class="page-header-right ms-auto">
            <div class="page-header-right-items">
                <div class="d-flex d-md-none">
                    <a href="javascript:void(0)" class="page-header-right-close-toggle">
                        <i class="feather-arrow-left me-2"></i>
                        <span>Quay lại</span>
                    </a>
                </div>
                <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                    <a href="{{route('admin.customer.showCreate')}}" class="btn btn-primary">
                        <i class="feather-plus me-2"></i>
                        <span>Thêm mới khách hàng</span>
                    </a>
                </div>
            </div>

            <div class="d-md-none d-flex align-items-center">
                <a href="javascript:void(0)" class="page-header-right-open-toggle">
                    <i class="feather-align-right fs-20"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card stretch stretch-full">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="customerList">
                                <thead class="table-light">
                                <tr>
                                    <th style="width:70px;">STT</th>
                                    <th>Khách hàng</th>
                                    <th style="width:200px;">Số điện thoại</th>
                                    <th style="width:220px;">Email</th>
                                    <th style="width:120px;">Giới tính</th>
                                    <th style="width:140px;">Ngày sinh</th>
                                    <th>Địa chỉ</th>
                                    <th style="width:160px;">Ngày tạo</th>
                                    <th class="text-end" style="width:140px;">Hành động</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($customers as $index => $customer)
                                    <tr>
                                        <td class="text-muted">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-text avatar-md">
                                                    {{ mb_substr($customer->full_name ?? 'K', 0, 1) }}
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold text-dark text-truncate" style="max-width: 260px;">
                                                        {{ $customer->full_name }}
                                                    </span>
                                                    <small class="text-muted">
                                                        ID: #{{ $customer->id }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="fw-semibold text-dark">
                                            {{ optional($customer->user)->phone ?? '—' }}
                                        </td>

                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                                {{ $customer->email ?? '—' }}
                                            </span>
                                        </td>

                                        <td>
                                            @php
                                                $gender = $customer->gender ?? '';
                                            @endphp
                                            <span class="badge bg-soft-primary text-primary">
                                                {{ $gender ?: '—' }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $customer->birthday ? \Carbon\Carbon::parse($customer->birthday)->format('d/m/Y') : '—' }}
                                        </td>

                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 340px;">
                                                {{ $customer->address ?? '—' }}
                                            </span>
                                        </td>

                                        <td class="text-muted">
                                            {{ $customer->created_at ? $customer->created_at->format('d/m/Y H:i') : '—' }}
                                        </td>

                                        <td class="text-end">
                                            <div class="dropdown d-inline-block">
                                                <button
                                                    class="btn btn-light btn-sm"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    data-bs-boundary="viewport"
                                                    aria-expanded="false"
                                                    style="border: 1px solid #e9ecef;"
                                                >
                                                    <i class="feather feather-more-horizontal"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin.customer.showEdit', $customer->id) }}">
                                                            <i class="feather feather-edit-3 me-2"></i>
                                                            <span>Sửa</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin.customer.destroy', $customer->id) }}">
                                                            <i class="feather feather-trash-2 me-2"></i>
                                                            <span>Xóa</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            Chưa có khách hàng nào.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($customers, 'links'))
                            <div class="p-3">
                                {{ $customers->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #customerList th {
            font-size: 12px;
            font-weight: 600;
        }

        #customerList td {
            font-size: 12px;
        }

        #customerList .badge {
            font-size: 13px;
        }

        #customerList .dropdown-menu .dropdown-item {
            font-size: 12px;
        }
    </style>
@endsection
