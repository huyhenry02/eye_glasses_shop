@extends('admin.layouts.main')
@section('content')
    <div class="page-header">
        <div class="page-header-right ms-auto">
            <div class="page-header-right-items">
                <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                    <a href="{{ route('admin.employee.showCreate') }}" class="btn btn-primary">
                        <i class="feather-plus me-2"></i>
                        <span>Thêm mới nhân viên</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card stretch stretch-full">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="employeeList" style="font-size: 14.5px;">
                                <thead class="table-light">
                                <tr>
                                    <th style="width:70px;">STT</th>
                                    <th>Nhân viên</th>
                                    <th style="width:200px;">Số điện thoại</th>
                                    <th style="width:300px;">Email</th>
                                    <th style="width:160px;">Chức vụ</th>
                                    <th style="width:140px;">Trạng thái</th>
                                    <th style="width:160px;">Ngày tạo</th>
                                    <th class="text-end" style="width:140px;">Hành động</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($employees as $employee)
                                    <tr>
                                        <td class="text-muted">{{ $loop->iteration }}</td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-text avatar-md">
                                                    {{ mb_substr($employee->full_name ?? 'N', 0, 1) }}
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold text-dark text-truncate" style="max-width: 260px;">
                                                        {{ $employee->full_name ?? '—' }}
                                                    </span>
                                                    <small class="text-muted">
                                                        ID: #{{ $employee->id ?? '—' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="fw-semibold text-dark">
                                            {{ optional($employee->user)->phone ?? '—' }}
                                        </td>

                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 210px;">
                                                {{ $employee->email ?? '—' }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge bg-soft-primary text-primary">
                                                {{ $employee->position ?? '—' }}
                                            </span>
                                        </td>

                                        <td>
                                            @php
                                                $status = $employee->status ?? 'active';
                                                $isActive = in_array($status, ['active', 1, '1'], true);
                                            @endphp

                                            <span class="badge {{ $isActive ? 'bg-soft-success text-success' : 'bg-soft-secondary text-secondary' }}">
                                                {{ $isActive ? 'Đang làm' : 'Ngừng làm' }}
                                            </span>
                                        </td>

                                        <td class="text-muted">
                                            {{ !empty($employee->created_at) ? \Carbon\Carbon::parse($employee->created_at)->format('d/m/Y H:i') : '—' }}
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
                                                        <a class="dropdown-item" href="{{ route('admin.employee.showEdit', $employee->id) }}">
                                                            <i class="feather feather-edit-3 me-2"></i>
                                                            <span>Sửa</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="{{ route('admin.employee.destroy', $employee->id) }}"
                                                           onclick="return confirm('Bạn chắc chắn muốn xóa nhân viên này?')">
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
                                        <td colspan="8" class="text-center py-5 text-muted" style="font-size: 15px;">
                                            Chưa có nhân viên nào.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($employees, 'links'))
                            <div class="p-3">
                                {{ $employees->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #employeeList th {
            font-size: 12px;
            font-weight: 600;
        }

        #employeeList td {
            font-size: 12px;
        }

        #employeeList .badge {
            font-size: 13px;
        }

        #employeeList .dropdown-menu .dropdown-item {
            font-size: 12px;
        }
    </style>
@endsection
