<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Random\RandomException;
use Throwable;

class CheckoutController extends Controller
{
    protected function getCustomer(): ?Customer
    {
        $userId = auth()->id();

        if (!$userId) {
            return null;
        }

        return Customer::query()->where('user_id', $userId)->first();
    }

    protected function getCustomerId(): ?int
    {
        return $this->getCustomer()?->id;
    }

    protected function getProductPrice(Product $product): int
    {
        if (!empty($product->discount_price) && (int) $product->discount_price > 0) {
            return (int) $product->discount_price;
        }

        return (int) ($product->price ?? 0);
    }

    protected function getCartItems(int $customerId)
    {
        return Cart::query()
            ->with('product.category')
            ->where('customer_id', $customerId)
            ->latest('id')
            ->get();
    }

    protected function getSubtotal($cartItems): int
    {
        return (int) $cartItems->sum(function (Cart $item) {
            if (!$item->product) {
                return 0;
            }

            return $this->getProductPrice($item->product) * (int) $item->quantity;
        });
    }

    protected function validateCartItems($cartItems): void
    {
        foreach ($cartItems as $item) {
            $product = $item->product;

            if (!$product) {
                throw new \RuntimeException('Có sản phẩm trong giỏ hàng không còn tồn tại.');
            }

            if ((int) $product->is_active !== Product::STATUS_ACTIVE) {
                throw new \RuntimeException('Có sản phẩm trong giỏ hàng hiện không còn kinh doanh.');
            }

            if ((int) $item->quantity > (int) $product->stock_quantity) {
                throw new \RuntimeException('Số lượng một số sản phẩm đã vượt quá tồn kho hiện tại.');
            }
        }
    }

    /**
     * @throws RandomException
     */
    protected function generateOrderCode(): string
    {
        do {
            $orderCode = 'DH' . now()->format('YmdHis') . random_int(100, 999);
        } while (Order::query()->where('order_code', $orderCode)->exists());

        return $orderCode;
    }

    public function showCart(): RedirectResponse|View
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập để xem giỏ hàng.');
        }

        $cartItems = $this->getCartItems($customerId);
        $subtotal = $this->getSubtotal($cartItems);

        return view('customer.pages.cart', compact('cartItems', 'subtotal'));
    }

    public function addToCart(Request $request): RedirectResponse
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return redirect()->route('auth.showLogin')->with('error', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
        }

        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::query()->findOrFail((int) $data['product_id']);

        if ((int) $product->is_active !== Product::STATUS_ACTIVE) {
            return redirect()->back()->with('error', 'Sản phẩm hiện không còn kinh doanh.');
        }

        if ((int) $product->stock_quantity <= 0) {
            return redirect()->back()->with('error', 'Sản phẩm đã hết hàng.');
        }

        $cart = Cart::query()
            ->where('customer_id', $customerId)
            ->where('product_id', $product->id)
            ->first();

        $newQuantity = (int) ($data['quantity'] ?? 1);

        if ($cart) {
            $newQuantity += (int) $cart->quantity;
        }

        if ($newQuantity > (int) $product->stock_quantity) {
            return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho hiện tại.');
        }

        if ($cart) {
            $cart->update([
                'quantity' => $newQuantity,
            ]);
        } else {
            Cart::query()->create([
                'customer_id' => $customerId,
                'product_id' => $product->id,
                'quantity' => $newQuantity,
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function updateCart(Request $request, int $id): RedirectResponse
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập.');
        }

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = Cart::query()
            ->where('customer_id', $customerId)
            ->with('product')
            ->findOrFail($id);

        if (!$cart->product) {
            $cart->delete();

            return redirect()->route('customer.showCart')->with('error', 'Sản phẩm không còn tồn tại.');
        }

        if ((int) $data['quantity'] > (int) $cart->product->stock_quantity) {
            return redirect()->route('customer.showCart')->with('error', 'Số lượng vượt quá tồn kho hiện tại.');
        }

        $cart->update([
            'quantity' => (int) $data['quantity'],
        ]);

        return redirect()->route('customer.showCart')->with('success', 'Cập nhật giỏ hàng thành công.');
    }

    public function deleteCart(int $id): RedirectResponse
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập.');
        }

        $cart = Cart::query()->where('customer_id', $customerId)->findOrFail($id);
        $cart->delete();

        return redirect()->route('customer.showCart')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function showCheckout(): RedirectResponse|View
    {
        $customer = $this->getCustomer();
        $customerId = $customer?->id;

        if (!$customerId) {
            return redirect()->route('customer.showCart')->with('error', 'Vui lòng đăng nhập để thanh toán.');
        }

        $cartItems = $this->getCartItems($customerId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.showCart')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $subtotal = $this->getSubtotal($cartItems);

        return view('customer.pages.checkout', compact('cartItems', 'subtotal', 'customer'));
    }

    public function storeOrder(Request $request, PaymentService $paymentService): RedirectResponse
    {
        $customer = $this->getCustomer();
        $customerId = $customer?->id;

        if (!$customerId) {
            return redirect()->route('customer.showCart')->with('error', 'Vui lòng đăng nhập để đặt hàng.');
        }

        $data = $request->validate([
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_phone' => ['required', 'string', 'max:20'],
            'shipping_email' => ['nullable', 'email', 'max:255'],
            'shipping_address' => ['required', 'string'],
            'payment_method' => ['required', 'in:' . implode(',', array_keys(Order::PAYMENT_METHODS))],
        ]);

        $cartItems = $this->getCartItems($customerId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.showCart')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        try {
            $this->validateCartItems($cartItems);
        } catch (Throwable $e) {
            return redirect()->route('customer.showCart')->with('error', $e->getMessage());
        }

        $totalAmount = $this->getSubtotal($cartItems);
        $paymentMethod = $data['payment_method'];

        if ($paymentMethod === Order::PAYMENT_METHOD_COD) {
            try {
                DB::transaction(function () use ($cartItems, $customerId, $data, $totalAmount, $paymentService) {
                    $order = Order::query()->create([
                        'customer_id' => $customerId,
                        'order_code' => $this->generateOrderCode(),
                        'total_amount' => $totalAmount,
                        'status' => Order::STATUS_PENDING,
                        'payment_status' => Order::PAYMENT_STATUS_UNPAID,
                        'payment_method' => Order::PAYMENT_METHOD_COD,
                        'shipping_name' => $data['shipping_name'],
                        'shipping_phone' => $data['shipping_phone'],
                        'shipping_email' => $data['shipping_email'] ?: null,
                        'shipping_address' => $data['shipping_address'],
                    ]);

                    foreach ($cartItems as $item) {
                        $product = $item->product;
                        if (!$product) {
                            throw new \RuntimeException('Sản phẩm trong giỏ hàng không tồn tại.');
                        }

                        $price = $this->getProductPrice($product);
                        $quantity = (int) $item->quantity;

                        $paymentService->handleInventory($product, $quantity, 'create');
                        $product->save();

                        OrderDetail::query()->create([
                            'order_id' => $order->id,
                            'product_id' => $item->product_id,
                            'quantity' => $quantity,
                            'total_price' => $price * $quantity,
                        ]);
                    }

                    Cart::query()->where('customer_id', $customerId)->delete();
                });
            } catch (Throwable $e) {
                return redirect()->back()->withInput()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
            }

            return redirect()->route('customer.orders.index')->with('success', 'Đặt hàng thành công.');
        }

        try {
            $orderCode = $this->generateOrderCode();

            $checkoutData = [
                'customer_id' => $customerId,
                'code' => $orderCode,
                'total_amount' => $totalAmount,
                'status' => Order::STATUS_PENDING,
                'payment_status' => Order::PAYMENT_STATUS_PAID,
                'payment_method' => Order::PAYMENT_METHOD_VNPAY,
                'shipping_name' => $data['shipping_name'],
                'shipping_phone' => $data['shipping_phone'],
                'shipping_email' => $data['shipping_email'] ?: null,
                'shipping_address' => $data['shipping_address'],
                'cart' => $cartItems->map(function (Cart $item) {
                    $price = $this->getProductPrice($item->product);
                    $quantity = (int) $item->quantity;

                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'quantity' => $quantity,
                        'unit_price' => $price,
                        'line_total' => $price * $quantity,
                    ];
                })->values()->toArray(),
            ];

            Cache::put('checkout_' . $orderCode, $checkoutData, now()->addMinutes(30));

            return $paymentService->createVnpayRedirectUrl(
                $totalAmount,
                $orderCode,
                route('customer.vnpay.return')
            );
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
        }
    }

    public function index(): RedirectResponse|View
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return redirect()->route('customer.showCart')->with('error', 'Vui lòng đăng nhập.');
        }

        $orders = Order::query()
            ->withCount('orderDetails')
            ->where('customer_id', $customerId)
            ->latest('id')
            ->paginate(10);

        return view('customer.pages.orders', compact('orders'));
    }

    public function show(int $id): RedirectResponse|View
    {
        $customerId = $this->getCustomerId();

        if (!$customerId) {
            return redirect()->route('customer.showCart')->with('error', 'Vui lòng đăng nhập.');
        }

        $order = Order::query()
            ->with(['orderDetails.product', 'customer'])
            ->where('customer_id', $customerId)
            ->findOrFail($id);

        return view('customer.pages.order-detail', compact('order'));
    }

    public function vnpayReturn(Request $request, PaymentService $paymentService): RedirectResponse
    {
        return $paymentService->handleVnpayReturn(
            $request,
            function ($checkoutData, $req) use ($paymentService) {
                return DB::transaction(function () use ($checkoutData, $req, $paymentService) {
                    $order = Order::query()->where('order_code', $checkoutData['code'])->first();

                    if ($order && $order->payment_status === Order::PAYMENT_STATUS_PAID) {
                        return redirect()->route('customer.orders.index')->with('success', 'Đơn hàng đã được thanh toán trước đó.');
                    }

                    if (!$order) {
                        $order = Order::query()->create([
                            'customer_id' => $checkoutData['customer_id'],
                            'order_code' => $checkoutData['code'],
                            'total_amount' => $checkoutData['total_amount'],
                            'status' => $checkoutData['status'],
                            'payment_status' => Order::PAYMENT_STATUS_PAID,
                            'payment_method' => $checkoutData['payment_method'],
                            'shipping_name' => $checkoutData['shipping_name'],
                            'shipping_phone' => $checkoutData['shipping_phone'],
                            'shipping_email' => $checkoutData['shipping_email'],
                            'shipping_address' => $checkoutData['shipping_address'],
                            'payment_time' => now(),
                            'payment_transaction_id' => $req->get('vnp_TransactionNo') ?: $req->get('vnp_TxnRef'),
                            'payment_bank_code' => $req->get('vnp_BankCode'),
                            'payment_response_code' => $req->get('vnp_ResponseCode'),
                            'payment_secure_hash' => $req->get('vnp_SecureHash'),
                        ]);
                    } else {
                        $order->update([
                            'payment_time' => now(),
                            'payment_status' => Order::PAYMENT_STATUS_PAID,
                            'payment_transaction_id' => $req->get('vnp_TransactionNo') ?: $req->get('vnp_TxnRef'),
                            'payment_bank_code' => $req->get('vnp_BankCode'),
                            'payment_response_code' => $req->get('vnp_ResponseCode'),
                            'payment_secure_hash' => $req->get('vnp_SecureHash'),
                        ]);
                        $order->orderDetails()->delete();
                    }

                    foreach ($checkoutData['cart'] as $item) {
                        $product = Product::query()->find($item['product_id']);

                        if (!$product) {
                            throw new \RuntimeException('Sản phẩm trong đơn hàng không tồn tại.');
                        }

                        $quantity = (int) $item['quantity'];
                        $lineTotal = (int) ($item['line_total'] ?? 0);

                        if ($lineTotal <= 0) {
                            $lineTotal = (int) ($item['unit_price'] ?? 0) * $quantity;
                        }

                        $paymentService->handleInventory($product, $quantity, 'create');
                        $product->save();

                        OrderDetail::query()->create([
                            'order_id' => $order->id,
                            'product_id' => $item['product_id'],
                            'quantity' => $quantity,
                            'total_price' => $lineTotal,
                        ]);
                    }

                    $cartIds = collect($checkoutData['cart'])->pluck('id')->filter()->values()->all();

                    if (!empty($cartIds)) {
                        Cart::query()
                            ->where('customer_id', $checkoutData['customer_id'])
                            ->whereIn('id', $cartIds)
                            ->delete();
                    }

                    return redirect()->route('customer.orders.index')->with('success', 'Thanh toán thành công!');
                });
            },
            fn($message) => redirect()->route('customer.showCart')->with('error', $message),
            ['checkout_']
        );
    }
}
