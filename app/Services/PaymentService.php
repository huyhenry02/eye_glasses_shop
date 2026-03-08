<?php

namespace App\Services;

use App\Models\Product;
use Closure;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;
use RuntimeException;

class PaymentService
{
    protected string $vnpTmnCode = "PHXIYIGL";
    protected string $vnpHashSecret = "UXKJ13ET1O98DNIVOMZ69VQ2YOH8XGTS";
    protected string $vnpUrl = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";

    /**
     * Redirect to VNPAY with valid parameters
     */
    public function createVnpayRedirectUrl(int $amount, string $code, string $returnUrl): RedirectResponse
    {
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnpTmnCode,
            "vnp_Amount" => $amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => 'vn',
            "vnp_OrderInfo" => 'Thanh toan hoa don' . $code,
            "vnp_OrderType" => 'other',
            "vnp_ReturnUrl" => $returnUrl,
            "vnp_TxnRef" => $code,
            "vnp_BankCode" => 'NCB',
        ];

        ksort($inputData);
        $query = '';
        $hashData = '';
        foreach ($inputData as $key => $value) {
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
            $hashData .= ($hashData ? '&' : '') . urlencode($key) . '=' . urlencode($value);
        }
        $vnpSecureHash = hash_hmac('sha512', $hashData, $this->vnpHashSecret);
        $redirectUrl = $this->vnpUrl . '?' . $query . 'vnp_SecureHash=' . $vnpSecureHash;
        return redirect($redirectUrl);
    }

    /**
     * Handle VNPAY return with injected callbacks for success/failure
     */
    public function handleVnpayReturn(Request $request, string $sessionKey, Closure $onSuccess, Closure $onFail)
    {
        try {
            $checkoutData = Session::get($sessionKey);
            $vnp_ResponseCode = $request->get('vnp_ResponseCode');
            $vnp_TxnRef = $request->get('vnp_TxnRef');

            $inputData = $request->except('vnp_SecureHash', 'vnp_SecureHashType');
            ksort($inputData);

            $hashData = '';
            foreach ($inputData as $key => $value) {
                $hashData .= $key . '=' . urlencode($value) . '&';
            }
            $hashData = rtrim($hashData, '&');

            $secureHashCheck = hash_hmac('sha512', $hashData, $this->vnpHashSecret);
            $vnp_SecureHash = $request->get('vnp_SecureHash');

            if (
                $secureHashCheck === $vnp_SecureHash &&
                $checkoutData &&
                ($checkoutData['code'] ?? '') === $vnp_TxnRef &&
                $vnp_ResponseCode === '00'
            ) {
                DB::beginTransaction();
                $result = $onSuccess($checkoutData, $request);
                DB::commit();
                Session::forget($sessionKey);
                return $result;
            }

            return $onFail('Dữ liệu không hợp lệ hoặc thanh toán thất bại');
        } catch (Exception $e) {
            DB::rollBack();
            return $onFail('Lỗi xử lý: ' . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function handleInventory(Product $product, int $quantity, $type): void
    {
        if ($type === 'create') {
            if ($product->stock_quantity < $quantity) {
                throw new RuntimeException("Không đủ số lượng trong kho để tạo đơn hàng.");
            }

            $product->stock_quantity -= $quantity;
        } elseif ($type === 'cancel') {
            $product->stock_quantity += $quantity;
        } else {
            throw new InvalidArgumentException("Loại hành động không hợp lệ: $type");
        }
    }
}
