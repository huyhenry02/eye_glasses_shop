<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Random\RandomException;
use Throwable;

class InvoiceController extends Controller
{
    protected function getEmployeeId(): ?int
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }
        return Employee::where('user_id', $user->id)->first();
    }

    protected function getProductPrice(Product $product): int
    {
        if (!empty($product->discount_price) && (int) $product->discount_price > 0) {
            return (int) $product->discount_price;
        }

        return (int) ($product->price ?? 0);
    }

    /**
     * @throws RandomException
     */
    protected function generateInvoiceCode(): string
    {
        do {
            $invoiceCode = 'HD' . now()->format('YmdHis') . random_int(100, 999);
        } while (Invoice::where('invoice_code', $invoiceCode)->exists());

        return $invoiceCode;
    }

    protected function getProducts()
    {
        return Product::query()
            ->where('is_active', Product::STATUS_ACTIVE)
            ->orderBy('name')
            ->get();
    }

    protected function validateInvoiceRequest(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'payment_method' => ['required', Rule::in(array_keys(Invoice::PAYMENT_METHODS))],
            'status' => [$isUpdate ? 'required' : 'nullable', Rule::in(array_keys(Invoice::STATUSES))],
            'payment_status' => [$isUpdate ? 'required' : 'nullable', Rule::in(array_keys(Invoice::PAYMENT_STATUSES))],
            'product_id' => ['required', 'array', 'min:1'],
            'product_id.*' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'array', 'min:1'],
            'quantity.*' => ['required', 'integer', 'min:1'],
        ]);
    }

    protected function buildInvoiceItems(array $data): array
    {
        $items = [];
        $productIds = $data['product_id'] ?? [];
        $quantities = $data['quantity'] ?? [];

        foreach ($productIds as $index => $productId) {
            $quantity = (int) ($quantities[$index] ?? 0);

            if ($quantity < 1) {
                continue;
            }

            $product = Product::findOrFail((int) $productId);

            if ((int) $product->is_active !== Product::STATUS_ACTIVE) {
                throw new \RuntimeException('Một trong các sản phẩm đã ngừng kinh doanh.');
            }

            $unitPrice = $this->getProductPrice($product);

            $items[] = [
                'product' => $product,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $unitPrice * $quantity,
            ];
        }

        if ($items === []) {
            throw new \RuntimeException('Vui lòng chọn ít nhất một sản phẩm hợp lệ.');
        }

        return $items;
    }

    protected function checkStockBeforeSave(array $items): void
    {
        foreach ($items as $item) {
            $product = $item['product'];
            $quantity = (int) $item['quantity'];

            if ((int) $product->stock_quantity < $quantity) {
                throw new \RuntimeException('Sản phẩm "' . ($product->name ?? 'N/A') . '" không đủ tồn kho.');
            }
        }
    }

    protected function restoreInventoryFromInvoice(Invoice $invoice, PaymentService $paymentService): void
    {
        if ($invoice->status !== Invoice::STATUS_COMPLETED) {
            return;
        }

        foreach ($invoice->invoiceDetails as $detail) {
            if (!$detail->product) {
                continue;
            }

            $paymentService->handleInventory($detail->product, (int) $detail->quantity, 'cancel');
            $detail->product->save();
        }
    }

    protected function saveInvoiceDetails(Invoice $invoice, array $items, PaymentService $paymentService, bool $shouldDeductInventory): void
    {
        foreach ($items as $item) {
            $product = $item['product'];
            $quantity = (int) $item['quantity'];

            if ($shouldDeductInventory) {
                $paymentService->handleInventory($product, $quantity, 'create');
                $product->save();
            }

            InvoiceDetail::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'quantity' => $quantity,
                'total_price' => $item['line_total'],
            ]);
        }
    }

    protected function buildPaymentPayload(array $data, array $items, int $totalAmount, ?int $invoiceId, string $invoiceCode): array
    {
        return [
            'invoice_id' => $invoiceId,
            'code' => $invoiceCode,
            'customer_name' => $data['customer_name'] ?? null,
            'customer_phone' => $data['customer_phone'] ?? null,
            'total_amount' => $totalAmount,
            'status' => Invoice::STATUS_COMPLETED,
            'payment_status' => Invoice::PAYMENT_STATUS_PAID,
            'payment_method' => Invoice::PAYMENT_METHOD_VNPAY,
            'employee_id' => 1,
            'items' => array_map(static function (array $item) {
                return [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['line_total'],
                ];
            }, $items),
        ];
    }

    public function showIndex(Request $request)
    {
        try {
            $keyword = trim((string) $request->keyword);
            $status = trim((string) $request->status);
            $paymentStatus = trim((string) $request->payment_status);
            $paymentMethod = trim((string) $request->payment_method);

            $invoices = Invoice::with(['employee'])
                ->withCount('invoiceDetails')
                ->when($keyword !== '', function ($query) use ($keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('invoice_code', 'like', '%' . $keyword . '%')
                            ->orWhere('customer_name', 'like', '%' . $keyword . '%')
                            ->orWhere('customer_phone', 'like', '%' . $keyword . '%');
                    });
                })
                ->when($status !== '', fn($q) => $q->where('status', $status))
                ->when($paymentStatus !== '', fn($q) => $q->where('payment_status', $paymentStatus))
                ->when($paymentMethod !== '', fn($q) => $q->where('payment_method', $paymentMethod))
                ->latest('id')
                ->paginate(10)
                ->withQueryString();

            return view('admin.pages.invoice.index', compact('invoices'));
        } catch (Throwable $e) {
            return back()->with('error', 'Không thể tải danh sách hóa đơn.');
        }
    }

    public function showCreate()
    {
        $invoice = new Invoice([
            'status' => Invoice::STATUS_DRAFT,
            'payment_method' => Invoice::PAYMENT_METHOD_CASH,
            'payment_status' => Invoice::PAYMENT_STATUS_UNPAID,
        ]);
        $products = $this->getProducts();
        $isEdit = false;

        return view('admin.pages.invoice.edit', compact('invoice', 'products', 'isEdit'));
    }

    public function showEdit($id)
    {
        try {
            $invoice = Invoice::with(['invoiceDetails.product', 'employee'])->findOrFail($id);
            $products = $this->getProducts();
            $isEdit = true;

            return view('admin.pages.invoice.edit', compact('invoice', 'products', 'isEdit'));
        } catch (Throwable $e) {
            return redirect()->route('admin.invoice.showIndex')->with('error', 'Không tìm thấy hóa đơn.');
        }
    }

    public function store(Request $request, PaymentService $paymentService): RedirectResponse
    {
        try {
            $data = $this->validateInvoiceRequest($request);
            $items = $this->buildInvoiceItems($data);
            $totalAmount = (int) collect($items)->sum('line_total');

            if ($data['payment_method'] === Invoice::PAYMENT_METHOD_VNPAY) {
                $this->checkStockBeforeSave($items);

                $invoiceCode = $this->generateInvoiceCode();
                $payload = $this->buildPaymentPayload($data, $items, $totalAmount, null, $invoiceCode);

                Cache::put('admin_invoice_checkout_' . $invoiceCode, $payload, now()->addMinutes(30));

                return $paymentService->createVnpayRedirectUrl(
                    $totalAmount,
                    $invoiceCode,
                    route('admin.invoice.vnpayReturn')
                );
            }

            DB::transaction(function () use ($data, $items, $totalAmount, $paymentService) {
                $this->checkStockBeforeSave($items);

                $invoice = Invoice::create([
                    'invoice_code' => $this->generateInvoiceCode(),
                    'customer_name' => $data['customer_name'] ?? null,
                    'customer_phone' => $data['customer_phone'] ?? null,
                    'total_amount' => $totalAmount,
                    'status' => Invoice::STATUS_COMPLETED,
                    'payment_method' => Invoice::PAYMENT_METHOD_CASH,
                    'payment_status' => Invoice::PAYMENT_STATUS_PAID,
                    'payment_time' => now(),
                    'employee_id' => 1,
                ]);

                $this->saveInvoiceDetails($invoice, $items, $paymentService, true);
            });

            return redirect()->route('admin.invoice.showIndex')->with('success', 'Tạo hóa đơn thành công.');
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'Tạo hóa đơn thất bại: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id, PaymentService $paymentService): RedirectResponse
    {
        try {
            $invoice = Invoice::with(['invoiceDetails.product'])->findOrFail($id);
            $data = $this->validateInvoiceRequest($request, true);
            $items = $this->buildInvoiceItems($data);
            $totalAmount = (int) collect($items)->sum('line_total');

            if (
                $data['payment_method'] === Invoice::PAYMENT_METHOD_VNPAY
                && $data['payment_status'] !== Invoice::PAYMENT_STATUS_PAID
            ) {
                $this->checkStockBeforeSave($items);

                $payload = $this->buildPaymentPayload($data, $items, $totalAmount, $invoice->id, $invoice->invoice_code);

                Cache::put('admin_invoice_checkout_' . $invoice->invoice_code, $payload, now()->addMinutes(30));

                return $paymentService->createVnpayRedirectUrl(
                    $totalAmount,
                    $invoice->invoice_code,
                    route('admin.invoice.vnpayReturn')
                );
            }

            DB::transaction(function () use ($invoice, $data, $items, $totalAmount, $paymentService) {
                $this->restoreInventoryFromInvoice($invoice, $paymentService);
                $invoice->invoiceDetails()->delete();

                if (($data['status'] ?? Invoice::STATUS_DRAFT) === Invoice::STATUS_COMPLETED) {
                    $this->checkStockBeforeSave($items);
                }

                $invoice->update([
                    'customer_name' => $data['customer_name'] ?? null,
                    'customer_phone' => $data['customer_phone'] ?? null,
                    'total_amount' => $totalAmount,
                    'status' => $data['status'],
                    'payment_method' => $data['payment_method'],
                    'payment_status' => $data['payment_status'],
                    'payment_time' => $data['payment_status'] === Invoice::PAYMENT_STATUS_PAID
                        ? ($invoice->payment_time ?? now())
                        : null,
                    'payment_transaction_id' => $data['payment_method'] === Invoice::PAYMENT_METHOD_CASH
                        ? null
                        : $invoice->payment_transaction_id,
                    'payment_bank_code' => $data['payment_method'] === Invoice::PAYMENT_METHOD_CASH
                        ? null
                        : $invoice->payment_bank_code,
                    'payment_response_code' => $data['payment_method'] === Invoice::PAYMENT_METHOD_CASH
                        ? null
                        : $invoice->payment_response_code,
                    'payment_secure_hash' => $data['payment_method'] === Invoice::PAYMENT_METHOD_CASH
                        ? null
                        : $invoice->payment_secure_hash,
                    'employee_id' => 1,
                ]);

                $this->saveInvoiceDetails(
                    $invoice,
                    $items,
                    $paymentService,
                    $data['status'] === Invoice::STATUS_COMPLETED
                );
            });

            return redirect()->route('admin.invoice.showIndex')->with('success', 'Cập nhật hóa đơn thành công.');
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'Cập nhật hóa đơn thất bại: ' . $e->getMessage());
        }
    }

    public function destroy($id, PaymentService $paymentService): RedirectResponse
    {
        try {
            $invoice = Invoice::with(['invoiceDetails.product'])->findOrFail($id);

            DB::transaction(function () use ($invoice, $paymentService) {
                $this->restoreInventoryFromInvoice($invoice, $paymentService);
                $invoice->invoiceDetails()->delete();
                $invoice->delete();
            });

            return redirect()->route('admin.invoice.showIndex')->with('success', 'Xóa hóa đơn thành công.');
        } catch (Throwable $e) {
            return redirect()->route('admin.invoice.showIndex')->with('error', 'Xóa hóa đơn thất bại.');
        }
    }

    public function vnpayReturn(Request $request, PaymentService $paymentService): RedirectResponse
    {
        return $paymentService->handleVnpayReturn(
            $request,
            function ($checkoutData, $req) use ($paymentService) {
                return DB::transaction(function () use ($checkoutData, $req, $paymentService) {
                    $invoice = null;

                    if (!empty($checkoutData['invoice_id'])) {
                        $invoice = Invoice::with(['invoiceDetails.product'])->find($checkoutData['invoice_id']);
                    }

                    if (!$invoice) {
                        $invoice = Invoice::with(['invoiceDetails.product'])
                            ->where('invoice_code', $checkoutData['code'])
                            ->first();
                    }

                    if ($invoice && $invoice->payment_status === Invoice::PAYMENT_STATUS_PAID) {
                        return redirect()->route('admin.invoice.showEdit', $invoice->id)
                            ->with('success', 'Hóa đơn đã được thanh toán trước đó.');
                    }

                    if ($invoice) {
                        $this->restoreInventoryFromInvoice($invoice, $paymentService);
                        $invoice->invoiceDetails()->delete();

                        $invoice->update([
                            'customer_name' => $checkoutData['customer_name'] ?? null,
                            'customer_phone' => $checkoutData['customer_phone'] ?? null,
                            'total_amount' => (int) ($checkoutData['total_amount'] ?? 0),
                            'status' => $checkoutData['status'],
                            'payment_method' => $checkoutData['payment_method'],
                            'payment_status' => $checkoutData['payment_status'],
                            'payment_time' => now(),
                            'payment_transaction_id' => (string) $req->get('vnp_TransactionNo', ''),
                            'payment_bank_code' => (string) $req->get('vnp_BankCode', ''),
                            'payment_response_code' => (string) $req->get('vnp_ResponseCode', ''),
                            'payment_secure_hash' => (string) $req->get('vnp_SecureHash', ''),
                            'employee_id' => 1,
                        ]);
                    } else {
                        $invoice = Invoice::create([
                            'invoice_code' => $checkoutData['code'],
                            'customer_name' => $checkoutData['customer_name'] ?? null,
                            'customer_phone' => $checkoutData['customer_phone'] ?? null,
                            'total_amount' => (int) ($checkoutData['total_amount'] ?? 0),
                            'status' => $checkoutData['status'],
                            'payment_method' => $checkoutData['payment_method'],
                            'payment_status' => $checkoutData['payment_status'],
                            'payment_time' => now(),
                            'payment_transaction_id' => (string) $req->get('vnp_TransactionNo', ''),
                            'payment_bank_code' => (string) $req->get('vnp_BankCode', ''),
                            'payment_response_code' => (string) $req->get('vnp_ResponseCode', ''),
                            'payment_secure_hash' => (string) $req->get('vnp_SecureHash', ''),
                            'employee_id' => 1,
                        ]);
                    }

                    $items = [];
                    foreach (($checkoutData['items'] ?? []) as $item) {
                        $product = Product::findOrFail((int) ($item['product_id'] ?? 0));
                        if ((int) $product->stock_quantity < (int) ($item['quantity'] ?? 0)) {
                            throw new \RuntimeException('Một số sản phẩm trong hóa đơn không còn đủ tồn kho.');
                        }

                        $items[] = [
                            'product' => $product,
                            'product_id' => $product->id,
                            'quantity' => (int) ($item['quantity'] ?? 0),
                            'line_total' => (int) ($item['line_total'] ?? 0),
                        ];
                    }

                    $this->saveInvoiceDetails($invoice, $items, $paymentService, true);

                    return redirect()->route('admin.invoice.showIndex', $invoice->id)
                        ->with('success', 'Thanh toán VNPay thành công.');
                });
            },
            function ($message) {
                return redirect()->route('admin.invoice.showEdit')->with('error', $message);
            },
            ['admin_invoice_checkout_']
        );
    }
}
