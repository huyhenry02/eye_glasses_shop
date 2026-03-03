@extends('admin.layouts.main')
@section('content')
    @php
        $isEdit = ($mode ?? 'create') === 'edit';
        $title = $isEdit ? 'Cập nhật sản phẩm' : 'Thêm mới sản phẩm';

        $categoryId = old('category_id', $product->category_id ?? '');
        $code = old('code', $product->code ?? '');
        $name = old('name', $product->name ?? '');
        $slug = old('slug', $product->slug ?? '');
        $description = old('description', $product->description ?? '');
        $price = old('price', $product->price ?? '');
        $discountPrice = old('discount_price', $product->discount_price ?? '');
        $stock = old('stock_quantity', $product->stock_quantity ?? '');
        $color = old('color', $product->color ?? '');
        $material = old('material', $product->material ?? '');
        $style = old('style', $product->style ?? '');
        $isActive = old('is_active', isset($product) ? (string)$product->is_active : '1');

        $image = old('image', $product->image ?? '');
        $img1 = old('image_detail_1', $product->image_detail_1 ?? '');
        $img2 = old('image_detail_2', $product->image_detail_2 ?? '');
        $img3 = old('image_detail_3', $product->image_detail_3 ?? '');
    @endphp

    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">{{ $title }}</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Quản trị</a></li>
                <li class="breadcrumb-item"><a href="#">Sản phẩm</a></li>
                <li class="breadcrumb-item">{{ $isEdit ? 'Chỉnh sửa' : 'Thêm mới' }}</li>
            </ul>
        </div>

        <div class="page-header-right ms-auto">
            <div class="page-header-right-items">
                <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                    <button type="submit" form="productForm" class="btn btn-primary">
                        <i class="feather-save me-2"></i>
                        <span>{{ $isEdit ? 'Lưu thay đổi' : 'Lưu sản phẩm' }}</span>
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
                        <form id="productForm"
                              action="{{ $isEdit ? route('admin.product.update', $product->id) : route('admin.product.store') }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-8">
                                    <h6 class="fw-bold mb-3">Thông tin cơ bản</h6>
                                    <div class="row">
                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Danh mục</label>
                                            <select name="category_id" class="form-select" required>
                                                <option value="" disabled {{ $categoryId==='' ? 'selected' : '' }}>Chọn
                                                    danh mục
                                                </option>
                                                @foreach(($categories ?? []) as $cat)
                                                    <option
                                                        value="{{ $cat->id }}" {{ (string)$categoryId === (string)$cat->id ? 'selected' : '' }}>
                                                        {{ $cat->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Mã sản phẩm</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-hash"></i></span>
                                                <input type="text" name="code" class="form-control"
                                                       value="{{ $code }}"
                                                       placeholder="Ví dụ: SP001" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label class="form-label fw-semibold">Tên sản phẩm</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-box"></i></span>
                                                <input type="text" name="name" class="form-control"
                                                       value="{{ $name }}"
                                                       placeholder="Nhập tên sản phẩm" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label class="form-label fw-semibold">Slug</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-link"></i></span>
                                                <input type="text" name="slug" class="form-control"
                                                       value="{{ $slug }}"
                                                       placeholder="Ví dụ: giay-the-thao-nike-air" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Giá</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i
                                                        class="feather-dollar-sign"></i></span>
                                                <input type="number" name="price" class="form-control"
                                                       value="{{ $price }}"
                                                       placeholder="Nhập giá" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Giá khuyến mãi</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-tag"></i></span>
                                                <input type="number" name="discount_price" class="form-control"
                                                       value="{{ $discountPrice }}"
                                                       placeholder="Nhập giá khuyến mãi (nếu có)">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Tồn kho</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-layers"></i></span>
                                                <input type="number" name="stock_quantity" class="form-control"
                                                       value="{{ $stock }}"
                                                       placeholder="Số lượng tồn" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Trạng thái</label>
                                            <select name="is_active" class="form-select" required>
                                                <option value="1" {{ (string)$isActive === '1' ? 'selected' : '' }}>Đang
                                                    bán
                                                </option>
                                                <option value="0" {{ (string)$isActive === '0' ? 'selected' : '' }}>Tạm
                                                    ẩn
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-lg-4 mb-4">
                                            <label class="form-label fw-semibold">Màu sắc</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-droplet"></i></span>
                                                <input type="text" name="color" class="form-control"
                                                       value="{{ $color }}"
                                                       placeholder="Ví dụ: Trắng/Đen">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-4">
                                            <label class="form-label fw-semibold">Chất liệu</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-feather"></i></span>
                                                <input type="text" name="material" class="form-control"
                                                       value="{{ $material }}"
                                                       placeholder="Ví dụ: Da/Canvas">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-4">
                                            <label class="form-label fw-semibold">Kiểu dáng</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-star"></i></span>
                                                <input type="text" name="style" class="form-control"
                                                       value="{{ $style }}"
                                                       placeholder="Ví dụ: Sneaker">
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label class="form-label fw-semibold">Mô tả</label>
                                            <textarea name="description" class="form-control" rows="6"
                                                      placeholder="Nhập mô tả sản phẩm"
                                                      required>{{ $description }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <h6 class="fw-bold mb-3">Hình ảnh</h6>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Ảnh chính</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="feather-image"></i></span>
                                            <input type="file" name="image" class="form-control image-input"
                                                   data-preview="preview-main">
                                        </div>

                                        <div id="preview-main"
                                             class="mt-2 rounded d-flex align-items-center justify-content-center"
                                             style="width:100%;max-width:260px;height:180px;overflow:hidden;border:1px solid #e9ecef;background:#f8f9fa;">
                                            @if($image)
                                                <img src="{{ $image }}"
                                                     style="width:100%;height:100%;object-fit:cover;">
                                            @else
                                                <span class="text-muted">Chưa có ảnh</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Ảnh chi tiết 1</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="feather-image"></i></span>
                                            <input type="file" name="image_detail_1" class="form-control image-input"
                                                   data-preview="preview-1">
                                        </div>

                                        <div id="preview-1"
                                             class="mt-2 rounded d-flex align-items-center justify-content-center"
                                             style="width:100%;max-width:260px;height:140px;overflow:hidden;border:1px solid #e9ecef;background:#f8f9fa;">
                                            @if($img1)
                                                <img src="{{ $img1 }}" style="width:100%;height:100%;object-fit:cover;">
                                            @else
                                                <span class="text-muted">Chưa có ảnh</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Ảnh chi tiết 2</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="feather-image"></i></span>
                                            <input type="file" name="image_detail_2" class="form-control image-input"
                                                   data-preview="preview-2">
                                        </div>

                                        <div id="preview-2"
                                             class="mt-2 rounded d-flex align-items-center justify-content-center"
                                             style="width:100%;max-width:260px;height:140px;overflow:hidden;border:1px solid #e9ecef;background:#f8f9fa;">
                                            @if($img2)
                                                <img src="{{ $img2 }}" style="width:100%;height:100%;object-fit:cover;">
                                            @else
                                                <span class="text-muted">Chưa có ảnh</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Ảnh chi tiết 3</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="feather-image"></i></span>
                                            <input type="file" name="image_detail_3" class="form-control image-input"
                                                   data-preview="preview-3">
                                        </div>

                                        <div id="preview-3"
                                             class="mt-2 rounded d-flex align-items-center justify-content-center"
                                             style="width:100%;max-width:260px;height:140px;overflow:hidden;border:1px solid #e9ecef;background:#f8f9fa;">
                                            @if($img3)
                                                <img src="{{ $img3 }}" style="width:100%;height:100%;object-fit:cover;"
                                                     alt="">
                                            @else
                                                <span class="text-muted">Chưa có ảnh</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-2">
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.image-input').forEach(input => {
                input.addEventListener('change', function () {
                    const previewId = this.getAttribute('data-preview')
                    const previewBox = document.getElementById(previewId)
                    const file = this.files[0]

                    if (!file || !previewBox) return

                    if (!file.type.startsWith('image/')) {
                        alert('Vui lòng chọn file hình ảnh')
                        this.value = ''
                        return
                    }

                    const reader = new FileReader()
                    reader.onload = function (e) {
                        previewBox.innerHTML = `
                        <img src="${e.target.result}"
                             style="width:100%;height:100%;object-fit:cover;">
                    `
                    }
                    reader.readAsDataURL(file)
                })
            })
        })
    </script>

@endsection
