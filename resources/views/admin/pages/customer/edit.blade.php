@php use Carbon\Carbon; @endphp
@extends('admin.layouts.main')

@section('content')
    @php
        $isEdit = ($mode ?? 'create') === 'edit';
        $title = $isEdit ? 'Cập nhật khách hàng' : 'Thêm mới khách hàng';

        $fullName = old('full_name', $customer->full_name ?? '');
        $phone = old('phone', $customer->user->phone ?? '');
        $email = old('email', $customer->email ?? '');
        $gender = old('gender', $customer->gender ?? '');
        $birthday = old('birthday', isset($customer->birthday) ? Carbon::parse($customer->birthday)->format('Y-m-d') : '');
        $address = old('address', $customer->address ?? '');
    @endphp

    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">{{ $title }}</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Quản trị</a></li>
                <li class="breadcrumb-item"><a href="#">Khách hàng</a></li>
                <li class="breadcrumb-item">{{ $isEdit ? 'Chỉnh sửa' : 'Thêm mới' }}</li>
            </ul>
        </div>

        <div class="page-header-right ms-auto">
            <div class="page-header-right-items">
                <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                    <button type="submit" form="customerForm" class="btn btn-primary">
                        <i class="feather-save me-2"></i>
                        <span>{{ $isEdit ? 'Lưu thay đổi' : 'Lưu khách hàng' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-top-0">
                    <div class="card-body">
                        <form id="customerForm"
                              action="{{ $isEdit ? route('admin.customer.update', $customer->id) : route('admin.customer.store') }}"
                              method="POST">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-lg-12">
                                    <h6 class="fw-bold mb-3">Thông tin khách hàng</h6>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Họ và tên</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="feather-user"></i></span>
                                        <input type="text" name="full_name" class="form-control"
                                               value="{{ $fullName }}"
                                               placeholder="Nhập họ và tên" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Số điện thoại</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="feather-phone"></i></span>
                                        <input type="text" name="phone" class="form-control"
                                               value="{{ $phone }}"
                                               placeholder="Nhập số điện thoại" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="feather-mail"></i></span>
                                        <input type="email" name="email" class="form-control"
                                               value="{{ $email }}"
                                               placeholder="Nhập email" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Giới tính</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="" disabled {{ $gender==='' ? 'selected' : '' }}>Chọn giới tính
                                        </option>
                                        <option value="Nam" {{ $gender==='Nam' ? 'selected' : '' }}>Nam</option>
                                        <option value="Nữ" {{ $gender==='Nữ' ? 'selected' : '' }}>Nữ</option>
                                        <option value="Khác" {{ $gender==='Khác' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Ngày sinh</label>
                                    <input type="date" name="birthday" class="form-control"
                                           value="{{ $birthday }}"
                                           required>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">
                                        Mật khẩu
                                        @if($isEdit)
                                            <span class="text-muted fw-normal">(bỏ trống nếu không đổi)</span>
                                        @endif
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="feather-lock"></i></span>
                                        <input type="password" name="password" class="form-control"
                                               placeholder="{{ $isEdit ? 'Nhập mật khẩu mới (nếu muốn đổi)' : 'Nhập mật khẩu' }}"
                                            {{ $isEdit ? '' : 'required' }}>
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-4">
                                    <label class="form-label fw-semibold">Địa chỉ</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="feather-map-pin"></i></span>
                                        <textarea name="address" class="form-control" rows="3"
                                                  placeholder="Nhập địa chỉ chi tiết"
                                                  required>{{ $address }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="#" class="btn btn-light">
                                    <i class="feather-arrow-left me-2"></i>
                                    Quay lại
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    <i class="feather-save me-2"></i>
                                    {{ $isEdit ? 'Lưu thay đổi' : 'Lưu' }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
