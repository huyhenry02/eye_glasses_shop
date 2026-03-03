@extends('admin.layouts.main')
@section('content')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Danh sách Danh mục</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Quản trị</a></li>
                <li class="breadcrumb-item">Danh mục</li>
            </ul>
        </div>

        <div class="page-header-right ms-auto">
            <div class="page-header-right-items">
                <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                    <a href="{{ route('admin.category.showCreate') }}" class="btn btn-primary">
                        <i class="feather-plus me-2"></i>
                        <span>Thêm mới danh mục</span>
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
                            <table class="table table-hover align-middle mb-0" id="categoryList" style="font-size: 14.5px;">
                                <thead class="table-light">
                                <tr>
                                    <th style="width:70px;">STT</th>
                                    <th style="width:140px;">Mã</th>
                                    <th style="width:220px;">Tên danh mục</th>
                                    <th style="width:260px;">Size áp dụng</th>
                                    <th>Mô tả</th>
                                    <th style="width:160px;">Ngày tạo</th>
                                    <th class="text-end" style="width:140px;">Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($categories as $category)
                                    @php
                                        $sizesRaw = $category->sizes ?? '';
                                        $sizesArr = array_values(array_filter(array_map('trim', explode(',', $sizesRaw))));
                                    @endphp

                                    <tr>
                                        <td class="text-muted">{{ $loop->iteration }}</td>

                                        <td class="fw-semibold text-dark">
                                            {{ $category->code ?? '—' }}
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold text-dark text-truncate" style="max-width: 220px;">
                                                    {{ $category->name ?? '—' }}
                                                </span>
                                                <small class="text-muted">ID: #{{ $category->id }}</small>
                                            </div>
                                        </td>

                                        <td>
                                            @if(count($sizesArr))
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($sizesArr as $s)
                                                        <span class="badge bg-soft-primary text-primary" style="font-weight:600;">
                                                            {{ $s }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 420px;">
                                                {{ $category->description ?? '—' }}
                                            </span>
                                        </td>

                                        <td class="text-muted">
                                            {{ $category->created_at ? $category->created_at->format('d/m/Y H:i') : '—' }}
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
                                                        <a class="dropdown-item" href="{{ route('admin.category.showEdit', $category->id) }}">
                                                            <i class="feather feather-edit-3 me-2"></i>
                                                            <span>Sửa</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="{{ route('admin.category.destroy', $category->id) }}"
                                                           onclick="return confirm('Bạn chắc chắn muốn xóa danh mục này?')">
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
                                        <td colspan="7" class="text-center py-5 text-muted" style="font-size: 15px;">
                                            Chưa có danh mục nào.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($categories, 'links'))
                            <div class="p-3">
                                {{ $categories->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #categoryList th {
            font-size: 12px;
            font-weight: 600;
        }

        #categoryList td {
            font-size: 12px;
        }

        #categoryList .badge {
            font-size: 13px;
        }

        #categoryList .dropdown-menu .dropdown-item {
            font-size: 12px;
        }
    </style>
@endsection
