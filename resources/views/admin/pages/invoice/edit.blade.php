@extends('admin.layouts.main')

@section('content')
    @php
        $isEdit = $isEdit ?? false;
        $title = $isEdit ? 'Cập nhật hóa đơn' : 'Tạo hóa đơn bán hàng';
        $invoiceCode = $invoice->invoice_code ?? 'Sẽ sinh tự động sau khi lưu';
        $customerName = old('customer_name', $invoice->customer_name ?? '');
        $customerPhone = old('customer_phone', $invoice->customer_phone ?? '');
        $paymentMethod = old('payment_method', $invoice->payment_method ?? \App\Models\Invoice::PAYMENT_METHOD_CASH);
        $status = old('status', $invoice->status ?? \App\Models\Invoice::STATUS_DRAFT);
        $paymentStatus = old('payment_status', $invoice->payment_status ?? \App\Models\Invoice::PAYMENT_STATUS_UNPAID);
        $productsById = $products->keyBy('id');

        $rowItems = [];

        if (old('product_id')) {
            $oldProductIds = old('product_id', []);
            $oldQuantities = old('quantity', []);

            foreach ($oldProductIds as $index => $productId) {
                $product = $productsById->get((int) $productId);
                $rowItems[] = [
                    'product_id' => $productId,
                    'quantity' => $oldQuantities[$index] ?? 1,
                    'unit_price' => $product?->final_price ?? 0,
                    'stock_quantity' => $product?->stock_quantity ?? 0,
                ];
            }
        } elseif ($isEdit && $invoice->relationLoaded('invoiceDetails') && $invoice->invoiceDetails->isNotEmpty()) {
            foreach ($invoice->invoiceDetails as $detail) {
                $rowItems[] = [
                    'product_id' => $detail->product_id,
                    'quantity' => (int) $detail->quantity,
                    'unit_price' => (int) ($detail->quantity > 0 ? ((int) $detail->total_price / (int) $detail->quantity) : 0),
                    'stock_quantity' => $detail->product?->stock_quantity ?? 0,
                ];
            }
        }

        if ($rowItems === []) {
            $rowItems[] = [
                'product_id' => '',
                'quantity' => 1,
                'unit_price' => 0,
                'stock_quantity' => 0,
            ];
        }
    @endphp

    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">{{ $title }}</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Quản trị</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.invoice.showIndex') }}">Hóa đơn</a></li>
                <li class="breadcrumb-item">{{ $isEdit ? 'Chỉnh sửa' : 'Tạo mới' }}</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <form
            action="{{ $isEdit ? route('admin.invoice.update', $invoice->id) : route('admin.invoice.store') }}"
            method="POST"
            id="invoiceForm"
        >
            @csrf

            <div class="row">
                <div class="col-xl-8">
                    <div class="card border-top-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                                <div>
                                    <h5 class="mb-1">Thông tin hóa đơn</h5>
                                    <p class="text-muted mb-0">Nhập thông tin khách hàng và danh sách sản phẩm cần bán.</p>
                                </div>
                                <div class="text-end">
                                    <div class="small text-muted">Mã hóa đơn</div>
                                    <div class="fw-semibold text-dark">{{ $invoiceCode }}</div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tên khách hàng</label>
                                    <input type="text" name="customer_name" class="form-control" value="{{ $customerName }}" placeholder="Khách lẻ / tên người mua">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Số điện thoại</label>
                                    <input type="text" name="customer_phone" class="form-control" value="{{ $customerPhone }}" placeholder="Nhập số điện thoại khách hàng">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Phương thức thanh toán</label>
                                    <select name="payment_method" class="form-select" id="paymentMethodSelect" required>
                                        @foreach(\App\Models\Invoice::PAYMENT_METHODS as $key => $label)
                                            <option value="{{ $key }}" {{ $paymentMethod === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @if($isEdit)
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Trạng thái hóa đơn</label>
                                        <select name="status" class="form-select" required>
                                            @foreach(\App\Models\Invoice::STATUSES as $key => $label)
                                                <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Trạng thái thanh toán</label>
                                        <select name="payment_status" class="form-select" required>
                                            @foreach(\App\Models\Invoice::PAYMENT_STATUSES as $key => $label)
                                                <option value="{{ $key }}" {{ $paymentStatus === $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>

                            <div class="alert alert-light border mt-4 mb-0" id="paymentMethodNote">
                                Chọn sản phẩm và bấm lưu để hoàn tất hóa đơn.
                            </div>
                        </div>
                    </div>

                    <div class="card border-top-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                                <div>
                                    <h5 class="mb-1">Danh sách sản phẩm</h5>
                                    <p class="text-muted mb-0">Một hóa đơn có thể chứa nhiều sản phẩm, mỗi sản phẩm có số lượng riêng.</p>
                                </div>
                                <button type="button" class="btn btn-primary" id="addRowBtn">
                                    <i class="feather-plus me-2"></i>
                                    Thêm sản phẩm
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0" id="invoiceItemsTable">
                                    <thead class="table-light">
                                    <tr>
                                        <th style="width: 32%;">Sản phẩm</th>
                                        <th style="width: 13%;">Đơn giá</th>
                                        <th style="width: 12%;">Số lượng</th>
                                        <th style="width: 13%;">Thành tiền</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($rowItems as $row)
                                        <tr class="invoice-item-row">
                                            <td>
                                                <select name="product_id[]" class="form-select product-select" required>
                                                    <option value="">Chọn sản phẩm</option>
                                                    @foreach($products as $product)
                                                        <option
                                                            value="{{ $product->id }}"
                                                            data-price="{{ (int) $product->final_price }}"
                                                            data-stock="{{ (int) $product->stock_quantity }}"
                                                            {{ (string) $row['product_id'] === (string) $product->id ? 'selected' : '' }}
                                                        >
                                                            {{ $product->name }} - {{ number_format($product->final_price, 0, ',', '.') }}đ
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="small text-muted mt-2 stock-text">
                                                    Tồn kho: {{ (int) $row['stock_quantity'] }}
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control unit-price-input" value="{{ number_format((int) $row['unit_price'], 0, ',', '.') }}đ" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="quantity[]" class="form-control quantity-input" min="1" value="{{ (int) $row['quantity'] }}" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control line-total-input" readonly>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-light text-danger remove-row-btn">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card border-top-0 sticky-top" style="top: 90px;">
                        <div class="card-body">
                            <h5 class="mb-3">Tóm tắt hóa đơn</h5>

                            <div class="summary-item d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Số dòng sản phẩm</span>
                                <strong id="summaryItemCount">0</strong>
                            </div>

                            <div class="summary-item d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Tổng số lượng</span>
                                <strong id="summaryQuantity">0</strong>
                            </div>

                            <div class="summary-item d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Tổng thanh toán</span>
                                <strong class="text-danger fs-5" id="summaryAmount">0đ</strong>
                            </div>

                            @if($isEdit)
                                <div class="summary-box border rounded p-3 bg-light mb-3">
                                    <div class="small text-muted mb-1">Thời gian tạo</div>
                                    <div class="fw-semibold">{{ $invoice->created_at?->format('d/m/Y H:i') ?? '-' }}</div>
                                </div>

                                <div class="summary-box border rounded p-3 bg-light mb-3">
                                    <div class="small text-muted mb-1">Nhân viên tạo / cập nhật</div>
                                    <div class="fw-semibold">{{ $invoice->employee->full_name ?? 'N/A' }}</div>
                                </div>
                            @endif

                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.invoice.showIndex') }}" class="btn btn-light">
                                    <i class="feather-arrow-left me-2"></i>
                                    Quay lại danh sách
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitButton">
                                    <i class="feather-save me-2"></i>
                                    {{ $isEdit ? 'Lưu thay đổi' : 'Tạo hóa đơn' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <template id="invoiceRowTemplate">
        <tr class="invoice-item-row">
            <td>
                <select name="product_id[]" class="form-select product-select mb-1" required>
                    <option value="">Chọn sản phẩm</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ (int) $product->final_price }}" data-stock="{{ (int) $product->stock_quantity }}">
                            {{ $product->name }} - {{ number_format($product->final_price, 0, ',', '.') }}đ
                        </option>
                    @endforeach
                </select>
                <div class="small text-muted mt-5 stock-text">Tồn kho: 0</div>
            </td>
            <td>
                <input type="text" class="form-control unit-price-input" value="0đ" readonly>
            </td>
            <td>
                <input type="number" name="quantity[]" class="form-control quantity-input" min="1" value="1" required>
            </td>
            <td>
                <input type="text" class="form-control line-total-input" value="0đ" readonly>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-light text-danger remove-row-btn">
                    <i class="feather-trash-2"></i>
                </button>
            </td>
        </tr>
    </template>

    <style>
        #invoiceItemsTable th,
        #invoiceItemsTable td {
            font-size: 13px;
            vertical-align: middle;
        }

        .summary-item strong,
        .summary-box .fw-semibold {
            font-size: 14px;
        }

        .sticky-top {
            z-index: 10;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableBody = document.querySelector('#invoiceItemsTable tbody');
            const template = document.getElementById('invoiceRowTemplate');
            const addRowBtn = document.getElementById('addRowBtn');
            const summaryItemCount = document.getElementById('summaryItemCount');
            const summaryQuantity = document.getElementById('summaryQuantity');
            const summaryAmount = document.getElementById('summaryAmount');
            const paymentMethodSelect = document.getElementById('paymentMethodSelect');
            const paymentMethodNote = document.getElementById('paymentMethodNote');
            const submitButton = document.getElementById('submitButton');

            const formatMoney = (number) => {
                return new Intl.NumberFormat('vi-VN').format(Number(number) || 0) + 'đ';
            };

            const updatePaymentNote = () => {
                if (!paymentMethodSelect) {
                    return;
                }

                if (paymentMethodSelect.value === '{{ \App\Models\Invoice::PAYMENT_METHOD_VNPAY }}') {
                    paymentMethodNote.textContent = 'Hệ thống sẽ chuyển sang cổng thanh toán VNPay sau khi kiểm tra dữ liệu hóa đơn.';
                    submitButton.innerHTML = '<i class="feather-credit-card me-2"></i>Tiếp tục thanh toán VNPay';
                    return;
                }

                paymentMethodNote.textContent = 'Hóa đơn tiền mặt sẽ được lưu ngay và trừ tồn kho sau khi xác nhận.';
                submitButton.innerHTML = '<i class="feather-save me-2"></i>{{ $isEdit ? 'Lưu thay đổi' : 'Tạo hóa đơn' }}';
            };

            const updateRow = (row) => {
                const select = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                const unitPriceInput = row.querySelector('.unit-price-input');
                const lineTotalInput = row.querySelector('.line-total-input');
                const stockText = row.querySelector('.stock-text');
                const selectedOption = select.options[select.selectedIndex];
                const unitPrice = Number(selectedOption?.dataset?.price || 0);
                const stock = Number(selectedOption?.dataset?.stock || 0);
                const quantity = Number(quantityInput.value || 0);
                const lineTotal = unitPrice * quantity;

                unitPriceInput.value = formatMoney(unitPrice);
                lineTotalInput.value = formatMoney(lineTotal);
                stockText.textContent = 'Tồn kho: ' + stock;
                quantityInput.max = stock > 0 ? stock : '';
            };

            const updateSummary = () => {
                const rows = Array.from(tableBody.querySelectorAll('.invoice-item-row'));
                let totalQuantity = 0;
                let totalAmount = 0;
                let validRows = 0;

                rows.forEach((row) => {
                    const select = row.querySelector('.product-select');
                    const quantity = Number(row.querySelector('.quantity-input').value || 0);
                    const selectedOption = select.options[select.selectedIndex];
                    const unitPrice = Number(selectedOption?.dataset?.price || 0);

                    if (select.value) {
                        validRows += 1;
                    }

                    totalQuantity += quantity;
                    totalAmount += unitPrice * quantity;
                });

                summaryItemCount.textContent = validRows;
                summaryQuantity.textContent = totalQuantity;
                summaryAmount.textContent = formatMoney(totalAmount);
            };

            const bindRowEvents = (row) => {
                const select = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                const removeBtn = row.querySelector('.remove-row-btn');

                select.addEventListener('change', function () {
                    updateRow(row);
                    updateSummary();
                });

                quantityInput.addEventListener('input', function () {
                    if (Number(this.value) < 1) {
                        this.value = 1;
                    }

                    updateRow(row);
                    updateSummary();
                });

                removeBtn.addEventListener('click', function () {
                    if (tableBody.querySelectorAll('.invoice-item-row').length === 1) {
                        return;
                    }

                    row.remove();
                    updateSummary();
                });

                updateRow(row);
            };

            Array.from(tableBody.querySelectorAll('.invoice-item-row')).forEach(bindRowEvents);
            updateSummary();
            updatePaymentNote();

            addRowBtn.addEventListener('click', function () {
                const clone = template.content.cloneNode(true);
                const row = clone.querySelector('.invoice-item-row');
                tableBody.appendChild(row);
                bindRowEvents(row);
                updateSummary();
            });

            if (paymentMethodSelect) {
                paymentMethodSelect.addEventListener('change', updatePaymentNote);
            }
        });
    </script>
@endsection
