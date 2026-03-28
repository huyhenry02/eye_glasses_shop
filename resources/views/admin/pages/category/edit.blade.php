@extends('admin.layouts.main')

@section('content')
    @php
        $isEdit = ($mode ?? 'create') === 'edit';
        $title = $isEdit ? 'Cập nhật danh mục' : 'Thêm mới danh mục';

        $code = old('code', $category->code ?? '');
        $name = old('name', $category->name ?? '');
        $slug = old('slug', $category->slug ?? '');
        $description = old('description', $category->description ?? '');
        $isActive = old('is_active', isset($category) ? (string) $category->is_active : '1');
    @endphp

    <div class="page-header">
        <div class="page-header-right ms-auto">
            <div class="page-header-right-items">
                <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                    <button type="submit" form="categoryForm" class="btn btn-primary">
                        <i class="feather-save me-2"></i>
                        <span>{{ $isEdit ? 'Lưu thay đổi' : 'Lưu danh mục' }}</span>
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
                        <form id="categoryForm"
                              action="{{ $isEdit ? route('admin.category.update', $category->id) : route('admin.category.store') }}"
                              method="POST">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-lg-12">
                                    <h6 class="fw-bold mb-3">Thông tin danh mục</h6>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Mã danh mục</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="feather-hash"></i></span>
                                        <input type="text" name="code" class="form-control"
                                               value="{{ $code }}"
                                               placeholder="Ví dụ: KINHRAM" required>
                                    </div>
                                    @error('code')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Tên danh mục</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="feather-tag"></i></span>
                                        <input type="text" name="name" class="form-control"
                                               value="{{ $name }}"
                                               placeholder="Ví dụ: Kính râm" required>
                                    </div>
                                    @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Slug</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="feather-link"></i></span>
                                        <input type="text" name="slug" class="form-control"
                                               value="{{ $slug }}"
                                               placeholder="Ví dụ: kinh-ram">
                                    </div>
                                    <div class="text-muted mt-2" style="font-size: 13px;">
                                        Có thể để trống, hệ thống sẽ tự sinh từ tên danh mục.
                                    </div>
                                    @error('slug')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Trạng thái</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="feather-toggle-right"></i></span>
                                        <select name="is_active" class="form-control">
                                            <option value="1" {{ $isActive === '1' ? 'selected' : '' }}>Hoạt động</option>
                                            <option value="0" {{ $isActive === '0' ? 'selected' : '' }}>Ngừng hoạt động</option>
                                        </select>
                                    </div>
                                    @error('is_active')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-12 mb-4">
                                    <label class="form-label fw-semibold">Mô tả</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="feather-align-left"></i></span>
                                        <textarea name="description" class="form-control" rows="4"
                                                  placeholder="Nhập mô tả (không bắt buộc)">{{ $description }}</textarea>
                                    </div>
                                    @error('description')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.category.showIndex') }}" class="btn btn-light">
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
