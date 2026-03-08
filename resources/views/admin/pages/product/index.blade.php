@extends('admin.layouts.main')
@section('content')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Danh sách Sản phẩm</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Quản trị</a></li>
                <li class="breadcrumb-item">Sản phẩm</li>
            </ul>
        </div>

        <div class="page-header-right ms-auto">
            <div class="page-header-right-items">
                <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                    <a href="{{ route('admin.product.showCreate') }}" class="btn btn-primary">
                        <i class="feather-plus me-2"></i>
                        <span>Thêm mới sản phẩm</span>
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
                            <table class="table table-hover align-middle mb-0" id="productList" style="font-size: 14.5px;">
                                <thead class="table-light">
                                <tr>
                                    <th style="width:70px;">STT</th>
                                    <th style="width:90px;">Ảnh</th>
                                    <th>Sản phẩm</th>
                                    <th style="width:180px;">Danh mục</th>
                                    <th style="width:140px;">Giá</th>
                                    <th style="width:140px;">Giá KM</th>
                                    <th style="width:120px;">Tồn kho</th>
                                    <th style="width:130px;">Trạng thái</th>
                                    <th style="width:160px;">Ngày tạo</th>
                                    <th class="text-end" style="width:140px;">Hành động</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($products as $product)
                                    @php
                                        $img = $product->image ? $product->image : null;
                                        $price = $product->price ?? 0;
                                        $discount = $product->discount_price ?? null;
                                        $isActive = (int)($product->is_active ?? 1) === 1;
                                    @endphp
                                    <tr>
                                        <td class="text-muted">{{ $loop->iteration }}</td>

                                        <td>
                                            <div class="rounded" style="width:64px;height:64px;overflow:hidden;border:1px solid #e9ecef;background:#f8f9fa;">
                                                @if($img)
                                                    <img src="{{ $img }}" alt="product" style="width:100%;height:100%;object-fit:cover;">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                        <i class="feather-image"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold text-dark text-truncate" style="max-width: 340px;">
                                                    {{ $product->name ?? '—' }}
                                                </span>
                                                <small class="text-muted">
                                                    Mã: <span class="fw-semibold">{{ $product->code ?? '—' }}</span>
                                                    <span class="mx-2">•</span>
                                                    Slug: <span class="text-truncate d-inline-block" style="max-width: 220px;vertical-align:bottom;">
                                                        {{ $product->slug ?? '—' }}
                                                    </span>
                                                </small>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="badge bg-soft-primary text-primary">
                                                {{ optional($product->category ?? null)->name ?? '—' }}
                                            </span>
                                        </td>

                                        <td class="fw-semibold text-dark">
                                            {{ number_format($price, 0, ',', '.') }}đ
                                        </td>

                                        <td>
                                            @if(!is_null($discount))
                                                <span class="fw-semibold text-success">
                                                    {{ number_format($discount, 0, ',', '.') }}đ
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ ($product->stock_quantity ?? 0) > 0 ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">
                                                {{ (int)($product->stock_quantity ?? 0) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $isActive ? 'bg-soft-success text-success' : 'bg-soft-secondary text-secondary' }}">
                                                {{ $isActive ? 'Đang bán' : 'Tạm ẩn' }}
                                            </span>
                                        </td>
                                        <td class="text-muted">
                                            {{ $product->created_at ? $product->created_at->format('d/m/Y H:i') : '—' }}
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
                                                        <a class="dropdown-item" href="{{ route('admin.product.showDetail' , $product->id) }}">
                                                            <i class="feather-eye me-2"></i>
                                                            <span>Xem</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.product.showEdit' , $product->id) }}">
                                                            <i class="feather-edit-3 me-2"></i>
                                                            <span>Sửa</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="{{ route('admin.product.destroy', $product->id) }}"
                                                           onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')">
                                                            <i class="feather-trash-2 me-2"></i>
                                                            <span>Xóa</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5 text-muted" style="font-size: 15px;">
                                            Chưa có sản phẩm nào.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($products, 'links'))
                            <div class="p-3">
                                {{ $products->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #productList th {
            font-size: 12px;
            font-weight: 600;
        }

        #productList td {
            font-size: 12px;
        }

        #productList .badge {
            font-size: 13px;
        }

        #productList .dropdown-menu .dropdown-item {
            font-size: 12px;
        }
    </style>
@endsection
