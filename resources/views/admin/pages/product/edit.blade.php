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
        $brand = old('brand', $product->brand ?? '');
        $frameMaterial = old('frame_material', $product->frame_material ?? '');
        $lensMaterial = old('lens_material', $product->lens_material ?? '');
        $shape = old('shape', $product->shape ?? '');
        $rimType = old('rim_type', $product->rim_type ?? '');
        $gender = old('gender', $product->gender ?? '');
        $frameColor = old('frame_color', $product->frame_color ?? '');
        $lensColor = old('lens_color', $product->lens_color ?? '');
        $colorsOld = old('colors', $product->colors ?? '');
        $lensWidth = old('lens_width', $product->lens_width ?? '');
        $bridgeWidth = old('bridge_width', $product->bridge_width ?? '');
        $templeLength = old('temple_length', $product->temple_length ?? '');
        $frameWidth = old('frame_width', $product->frame_width ?? '');
        $price = old('price', $product->price ?? '');
        $discountPrice = old('discount_price', $product->discount_price ?? '');
        $stock = old('stock_quantity', $product->stock_quantity ?? '');
        $isActive = old('is_active', isset($product) ? (string)$product->is_active : '1');
        $isFeatured = old('is_featured', isset($product) ? (string)$product->is_featured : '0');

        $image = $product->image ?? '';
        $img1 = $product->image_detail_1 ?? '';
        $img2 = $product->image_detail_2 ?? '';
        $img3 = $product->image_detail_3 ?? '';
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
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-lg-8">
                                    <h6 class="fw-bold mb-3">Thông tin cơ bản</h6>

                                    <div class="row">
                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Danh mục</label>
                                            <select name="category_id" class="form-select" required>
                                                <option value="" disabled {{ $categoryId === '' ? 'selected' : '' }}>
                                                    Chọn danh mục
                                                </option>
                                                @foreach(($categories ?? []) as $cat)
                                                    <option value="{{ $cat->id }}" {{ (string)$categoryId === (string)$cat->id ? 'selected' : '' }}>
                                                        {{ $cat->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Mã sản phẩm</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-hash"></i></span>
                                                <input type="text" name="code" class="form-control" value="{{ $code }}" placeholder="Ví dụ: KG001" required>
                                            </div>
                                            @error('code')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label class="form-label fw-semibold">Tên sản phẩm</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-box"></i></span>
                                                <input type="text" name="name" class="form-control" value="{{ $name }}" placeholder="Nhập tên sản phẩm" required>
                                            </div>
                                            @error('name')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label class="form-label fw-semibold">Slug</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-link"></i></span>
                                                <input type="text" name="slug" class="form-control" value="{{ $slug }}" placeholder="Có thể để trống để tự sinh">
                                            </div>
                                            @error('slug')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Thương hiệu</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-award"></i></span>
                                                <input type="text" name="brand" class="form-control" value="{{ $brand }}" placeholder="Ví dụ: Chemi, Hoya, Mắt Việt">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Giới tính</label>
                                            <select name="gender" class="form-select">
                                                <option value="">Chọn giới tính phù hợp</option>
                                                @foreach(\App\Models\Product::GENDERS as $value => $label)
                                                    <option value="{{ $value }}" {{ $gender === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Chất liệu gọng</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-feather"></i></span>
                                                <input type="text" name="frame_material" class="form-control" value="{{ $frameMaterial }}" placeholder="Ví dụ: Titan, TR90, Acetate">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Chất liệu tròng</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-circle"></i></span>
                                                <input type="text" name="lens_material" class="form-control" value="{{ $lensMaterial }}" placeholder="Ví dụ: Polarized, Blue Cut, 1.56">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-4">
                                            <label class="form-label fw-semibold">Dáng kính</label>
                                            <select name="shape" class="form-select">
                                                <option value="">Chọn dáng kính</option>
                                                @foreach(\App\Models\Product::SHAPES as $value => $label)
                                                    <option value="{{ $value }}" {{ $shape === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-4 mb-4">
                                            <label class="form-label fw-semibold">Kiểu viền</label>
                                            <select name="rim_type" class="form-select">
                                                <option value="">Chọn kiểu viền</option>
                                                @foreach(\App\Models\Product::RIM_TYPES as $value => $label)
                                                    <option value="{{ $value }}" {{ $rimType === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-4 mb-4">
                                            <label class="form-label fw-semibold">Nổi bật</label>
                                            <select name="is_featured" class="form-select" required>
                                                <option value="0" {{ (string)$isFeatured === '0' ? 'selected' : '' }}>Không</option>
                                                <option value="1" {{ (string)$isFeatured === '1' ? 'selected' : '' }}>Có</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Màu gọng</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-droplet"></i></span>
                                                <input type="text" name="frame_color" class="form-control" value="{{ $frameColor }}" placeholder="Ví dụ: Đen, Vàng, Bạc">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Màu tròng</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-droplet"></i></span>
                                                <input type="text" name="lens_color" class="form-control" value="{{ $lensColor }}" placeholder="Ví dụ: Xám, Nâu, Xanh">
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label class="form-label fw-semibold">Các màu khác</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-droplet"></i></span>
                                                <input type="text" class="form-control" id="colorInput" placeholder="Nhập màu rồi nhấn Enter">
                                            </div>

                                            <div id="colorContainer" class="mt-2 d-flex flex-wrap gap-2"></div>
                                            <input type="hidden" id="colorValues" name="colors" value="{{ $colorsOld }}">

                                            <div class="text-muted mt-2" style="font-size: 13px;">
                                                Gợi ý: Đen, Nâu đồi mồi, Trong suốt, Xanh navy...
                                            </div>
                                        </div>

                                        <div class="col-lg-3 mb-4">
                                            <label class="form-label fw-semibold">Rộng tròng (mm)</label>
                                            <input type="number" name="lens_width" class="form-control" value="{{ $lensWidth }}" placeholder="52">
                                        </div>

                                        <div class="col-lg-3 mb-4">
                                            <label class="form-label fw-semibold">Cầu kính (mm)</label>
                                            <input type="number" name="bridge_width" class="form-control" value="{{ $bridgeWidth }}" placeholder="18">
                                        </div>

                                        <div class="col-lg-3 mb-4">
                                            <label class="form-label fw-semibold">Dài càng (mm)</label>
                                            <input type="number" name="temple_length" class="form-control" value="{{ $templeLength }}" placeholder="145">
                                        </div>

                                        <div class="col-lg-3 mb-4">
                                            <label class="form-label fw-semibold">Ngang gọng (mm)</label>
                                            <input type="number" name="frame_width" class="form-control" value="{{ $frameWidth }}" placeholder="136">
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Giá</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-dollar-sign"></i></span>
                                                <input type="number" name="price" class="form-control" value="{{ $price }}" placeholder="Nhập giá" required>
                                            </div>
                                            @error('price')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Giá khuyến mãi</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-tag"></i></span>
                                                <input type="number" name="discount_price" class="form-control" value="{{ $discountPrice }}" placeholder="Nhập giá khuyến mãi">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Tồn kho</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="feather-layers"></i></span>
                                                <input type="number" name="stock_quantity" class="form-control" value="{{ $stock }}" placeholder="Số lượng tồn" required>
                                            </div>
                                            @error('stock_quantity')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 mb-4">
                                            <label class="form-label fw-semibold">Trạng thái</label>
                                            <select name="is_active" class="form-select" required>
                                                <option value="1" {{ (string)$isActive === '1' ? 'selected' : '' }}>Đang bán</option>
                                                <option value="0" {{ (string)$isActive === '0' ? 'selected' : '' }}>Tạm ẩn</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-12 mb-4">
                                            <label class="form-label fw-semibold">Mô tả</label>
                                            <textarea name="description" class="form-control" rows="6" placeholder="Nhập mô tả sản phẩm">{{ $description }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <h6 class="fw-bold mb-3">Hình ảnh</h6>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Ảnh chính</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="feather-image"></i></span>
                                            <input type="file" name="image" class="form-control image-input" data-preview="preview-main">
                                        </div>

                                        <div id="preview-main" class="mt-2 rounded d-flex align-items-center justify-content-center preview-box preview-main-box">
                                            @if($image)
                                                <img src="{{ $image }}" style="width:100%;height:100%;object-fit:contain;object-position:center;background:#fff;">
                                            @else
                                                <span class="text-muted">Chưa có ảnh</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Ảnh chi tiết 1</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="feather-image"></i></span>
                                            <input type="file" name="image_detail_1" class="form-control image-input" data-preview="preview-1">
                                        </div>

                                        <div id="preview-1" class="mt-2 rounded d-flex align-items-center justify-content-center preview-box preview-detail-box">
                                            @if($img1)
                                                <img src="{{ $img1 }}" style="width:100%;height:100%;object-fit:contain;object-position:center;background:#fff;">
                                            @else
                                                <span class="text-muted">Chưa có ảnh</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Ảnh chi tiết 2</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="feather-image"></i></span>
                                            <input type="file" name="image_detail_2" class="form-control image-input" data-preview="preview-2">
                                        </div>

                                        <div id="preview-2" class="mt-2 rounded d-flex align-items-center justify-content-center preview-box preview-detail-box">
                                            @if($img2)
                                                <img src="{{ $img2 }}" style="width:100%;height:100%;object-fit:contain;object-position:center;background:#fff;">
                                            @else
                                                <span class="text-muted">Chưa có ảnh</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Ảnh chi tiết 3</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="feather-image"></i></span>
                                            <input type="file" name="image_detail_3" class="form-control image-input" data-preview="preview-3">
                                        </div>

                                        <div id="preview-3" class="mt-2 rounded d-flex align-items-center justify-content-center preview-box preview-detail-box">
                                            @if($img3)
                                                <img src="{{ $img3 }}" style="width:100%;height:100%;object-fit:contain;object-position:center;background:#fff;">
                                            @else
                                                <span class="text-muted">Chưa có ảnh</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-2">
                                <a href="{{ route('admin.product.showIndex') }}" class="btn btn-light">
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

    <style>
        .color-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(13, 110, 253, 0.10);
            color: #0d6efd;
            font-weight: 600;
            font-size: 13px;
            border: 1px solid rgba(13, 110, 253, 0.18);
        }

        .color-pill button {
            border: none;
            background: transparent;
            color: #dc3545;
            font-weight: 900;
            line-height: 1;
            padding: 0;
            cursor: pointer;
            font-size: 16px;
        }

        .preview-box {
            width: 100%;
            max-width: 260px;
            overflow: hidden;
            border: 1px solid #e9ecef;
            background: #f8f9fa;
            padding: 10px;
        }

        .preview-main-box {
            height: 180px;
        }

        .preview-detail-box {
            height: 140px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.image-input').forEach(input => {
                input.addEventListener('change', function () {
                    const previewId = this.getAttribute('data-preview');
                    const previewBox = document.getElementById(previewId);
                    const file = this.files[0];

                    if (!file || !previewBox) return;

                    if (!file.type.startsWith('image/')) {
                        alert('Vui lòng chọn file hình ảnh');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewBox.innerHTML = `
                            <img src="${e.target.result}"
                                 style="width:100%;height:100%;object-fit:contain;object-position:center;background:#fff;">
                        `;
                    }
                    reader.readAsDataURL(file);
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const colorInput = document.getElementById("colorInput");
            const colorContainer = document.getElementById("colorContainer");
            const colorValues = document.getElementById("colorValues");

            let colors = [];

            function normalizeColor(s) {
                return (s || '').trim().replace(/\s+/g, ' ');
            }

            function updateColorDisplay() {
                colorContainer.innerHTML = "";
                colors.forEach((color, index) => {
                    const pill = document.createElement("span");
                    pill.className = "color-pill";
                    pill.innerHTML = `
                        ${color}
                        <button type="button" aria-label="Xóa màu" data-index="${index}">&times;</button>
                    `;
                    colorContainer.appendChild(pill);
                });
                colorValues.value = colors.join(", ");
            }

            function removeColor(index) {
                colors.splice(index, 1);
                updateColorDisplay();
            }

            const existing = (colorValues.value || "");
            existing.split(",").map(s => normalizeColor(s)).forEach(s => {
                if (s && !colors.includes(s)) colors.push(s);
            });
            updateColorDisplay();

            colorInput.addEventListener("keydown", function (event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    const colorText = normalizeColor(colorInput.value);

                    if (colorText && !colors.includes(colorText)) {
                        colors.push(colorText);
                        updateColorDisplay();
                    }

                    colorInput.value = "";
                }
            });

            colorContainer.addEventListener("click", function (e) {
                const btn = e.target.closest("button[data-index]");
                if (!btn) return;
                removeColor(Number(btn.getAttribute("data-index")));
            });
        });
    </script>
@endsection
