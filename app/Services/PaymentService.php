<?php

namespace App\Services;

use App\Models\Product;
use Closure;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use RuntimeException;

class PaymentService
{
    protected string $vnpTmnCode = 'PHXIYIGL';
    protected string $vnpHashSecret = 'UXKJ13ET1O98DNIVOMZ69VQ2YOH8XGTS';
    protected string $vnpUrl = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';

    public function createVnpayRedirectUrl(int $amount, string $code, string $returnUrl): RedirectResponse
    {
        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $this->vnpTmnCode,
            'vnp_Amount' => $amount * 100,
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => request()->ip(),
            'vnp_Locale' => 'vn',
            'vnp_OrderInfo' => 'Thanh toán hóa đơn ' . $code,
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => $returnUrl,
            'vnp_TxnRef' => $code,
            'vnp_BankCode' => 'NCB',
        ];

        ksort($inputData);

        $hashData = $this->buildHashData($inputData);
        $query = http_build_query($inputData, '', '&');
        $vnpSecureHash = hash_hmac('sha512', $hashData, $this->vnpHashSecret);

        $redirectUrl = $this->vnpUrl . '?' . $query . '&vnp_SecureHash=' . $vnpSecureHash;

        return redirect($redirectUrl);
    }

    public function handleVnpayReturn(
        Request $request,
        Closure $onSuccess,
        Closure $onFail,
        array $cachePrefixes = ['admin_invoice_checkout_', 'checkout_']
    ) {
        try {
            $responseCode = (string) $request->get('vnp_ResponseCode', '');
            $txnRef = (string) $request->get('vnp_TxnRef', '');
            $secureHash = (string) $request->get('vnp_SecureHash', '');

            if ($txnRef === '' || $secureHash === '') {
                return $onFail('Thiếu dữ liệu trả về từ VNPAY.');
            }

            $inputData = $request->except(['vnp_SecureHash', 'vnp_SecureHashType']);
            ksort($inputData);

            $hashData = $this->buildHashData($inputData);
            $secureHashCheck = hash_hmac('sha512', $hashData, $this->vnpHashSecret);

            if (!hash_equals($secureHashCheck, $secureHash)) {
                return $onFail('Chữ ký thanh toán không hợp lệ.');
            }

            $checkoutData = null;
            $matchedCacheKey = null;

            foreach ($cachePrefixes as $prefix) {
                $cacheKey = $prefix . $txnRef;
                $data = Cache::get($cacheKey);

                if (is_array($data) && ($data['code'] ?? '') === $txnRef) {
                    $checkoutData = $data;
                    $matchedCacheKey = $cacheKey;
                    break;
                }
            }

            if (!is_array($checkoutData) || $matchedCacheKey === null) {
                return $onFail('Không tìm thấy dữ liệu thanh toán tạm thời.');
            }

            if ($responseCode !== '00') {
                Cache::forget($matchedCacheKey);
                return $onFail('Thanh toán thất bại hoặc đã bị hủy.');
            }

            $result = $onSuccess($checkoutData, $request);
            Cache::forget($matchedCacheKey);

            return $result;
        } catch (Exception $e) {
            return $onFail('Lỗi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function handleInventory(Product $product, int $quantity, string $type): void
    {
        if ($type === 'create') {
            if ((int) $product->stock_quantity < $quantity) {
                throw new RuntimeException('Không đủ số lượng trong kho để tạo đơn hàng.');
            }

            $product->stock_quantity = (int) $product->stock_quantity - $quantity;
            return;
        }

        if ($type === 'cancel') {
            $product->stock_quantity = (int) $product->stock_quantity + $quantity;
            return;
        }

        throw new InvalidArgumentException('Loại hành động không hợp lệ: ' . $type);
    }

    private function buildHashData(array $inputData): string
    {
        $pairs = [];

        foreach ($inputData as $key => $value) {
            $pairs[] = urlencode((string) $key) . '=' . urlencode((string) $value);
        }

        return implode('&', $pairs);
    }
}
