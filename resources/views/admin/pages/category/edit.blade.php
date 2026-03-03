@extends('admin.layouts.main')

@section('content')
    @php
        $isEdit = ($mode ?? 'create') === 'edit';
        $title = $isEdit ? 'Cập nhật danh mục' : 'Thêm mới danh mục';

        $code = old('code', $category->code ?? '');
        $name = old('name', $category->name ?? '');
        $description = old('description', $category->description ?? '');
        $sizesOld = old('sizes', $category->sizes ?? '');
    @endphp

    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">{{ $title }}</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Quản trị</a></li>
                <li class="breadcrumb-item"><a href="#">Danh mục</a></li>
                <li class="breadcrumb-item">{{ $isEdit ? 'Chỉnh sửa' : 'Thêm mới' }}</li>
            </ul>
        </div>

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
                                               placeholder="Ví dụ: CAT001" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <label class="form-label fw-semibold">Tên danh mục</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="feather-tag"></i></span>
                                        <input type="text" name="name" class="form-control"
                                               value="{{ $name }}"
                                               placeholder="Ví dụ: Giày thể thao" required>
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-2">
                                    <label class="form-label fw-semibold">Size sản phẩm</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="feather-maximize"></i></span>
                                        <input type="text" class="form-control" id="sizeInput"
                                               placeholder="Nhập size (VD: S, M, L, XL) rồi nhấn Enter">
                                    </div>

                                    <div id="sizeContainer" class="mt-2 d-flex flex-wrap gap-2"></div>
                                    <input type="hidden" id="sizeValues" name="sizes" value="{{ $sizesOld }}">
                                    <div class="text-muted mt-2" style="font-size: 13px;">
                                        Gợi ý: Nhập từng size và nhấn Enter (VD: S, M, L, XL, XXL). Hệ thống sẽ lưu
                                        dạng: S,M,L,XL,XXL
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-4">
                                    <label class="form-label fw-semibold">Mô tả</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="feather-align-left"></i></span>
                                        <textarea name="description" class="form-control" rows="4"
                                                  placeholder="Nhập mô tả (không bắt buộc)">{{ $description }}</textarea>
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

                        <style>
                            .size-pill {
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

                            .size-pill button {
                                border: none;
                                background: transparent;
                                color: #dc3545;
                                font-weight: 900;
                                line-height: 1;
                                padding: 0;
                                cursor: pointer;
                                font-size: 16px;
                            }
                        </style>

                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const sizeInput = document.getElementById("sizeInput");
                                const sizeContainer = document.getElementById("sizeContainer");
                                const sizeValues = document.getElementById("sizeValues");

                                let sizes = [];

                                function normalizeSize(s) {
                                    return (s || '').trim().replace(/\s+/g, '').toUpperCase();
                                }

                                function updateSizeDisplay() {
                                    sizeContainer.innerHTML = "";
                                    sizes.forEach((size, index) => {
                                        const pill = document.createElement("span");
                                        pill.className = "size-pill";
                                        pill.innerHTML = `
                                            ${size}
                                            <button type="button" aria-label="Xóa size" data-index="${index}">&times;</button>
                                        `;
                                        sizeContainer.appendChild(pill);
                                    });
                                    sizeValues.value = sizes.join(",");
                                }

                                function removeSize(index) {
                                    sizes.splice(index, 1);
                                    updateSizeDisplay();
                                }

                                const existing = (sizeValues.value || "");
                                existing.split(",").map(s => normalizeSize(s)).forEach(s => {
                                    if (s && !sizes.includes(s)) sizes.push(s);
                                });
                                updateSizeDisplay();

                                sizeInput.addEventListener("keydown", function (event) {
                                    if (event.key === "Enter") {
                                        event.preventDefault();
                                        const sizeText = normalizeSize(sizeInput.value);

                                        if (sizeText && !sizes.includes(sizeText)) {
                                            sizes.push(sizeText);
                                            updateSizeDisplay();
                                        }

                                        sizeInput.value = "";
                                    }
                                });

                                sizeContainer.addEventListener("click", function (e) {
                                    const btn = e.target.closest("button[data-index]");
                                    if (!btn) return;
                                    removeSize(Number(btn.getAttribute("data-index")));
                                });
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
