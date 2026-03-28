<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'order_code',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'shipping_name',
        'shipping_phone',
        'shipping_email',
        'shipping_address',
        'completed_at',
        'payment_time',
        'payment_transaction_id',
        'payment_bank_code',
        'payment_response_code',
        'payment_secure_hash',
        'updated_by',
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'total_amount' => 'integer',
        'completed_at' => 'date',
        'payment_time' => 'datetime',
        'updated_by' => 'integer',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPING = 'shipping';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PENDING => 'Chờ xử lý',
        self::STATUS_PROCESSING => 'Đang xử lý',
        self::STATUS_SHIPPING => 'Đang giao hàng',
        self::STATUS_COMPLETED => 'Hoàn thành',
        self::STATUS_CANCELLED => 'Đã hủy',
    ];

    public const HEADER_STATUSES = [
        self::STATUS_PENDING => 'Đơn hàng mới',
        self::STATUS_PROCESSING => 'Đơn hàng đang xử lý',
        self::STATUS_SHIPPING => 'Đơn hàng đang giao',
        self::STATUS_COMPLETED => 'Đơn hàng đã được giao thành công',
        self::STATUS_CANCELLED => 'Đơn hàng đã bị hủy',
    ];

    public const PAYMENT_STATUS_UNPAID = 'unpaid';
    public const PAYMENT_STATUS_PAID = 'paid';
    public const PAYMENT_STATUS_FAILED = 'failed';

    public const PAYMENT_STATUSES = [
        self::PAYMENT_STATUS_UNPAID => 'Chưa thanh toán',
        self::PAYMENT_STATUS_PAID => 'Đã thanh toán',
        self::PAYMENT_STATUS_FAILED => 'Thanh toán thất bại',
    ];

    public const PAYMENT_METHOD_COD = 'cod';
    public const PAYMENT_METHOD_VNPAY = 'vnpay';

    public const PAYMENT_METHODS = [
        self::PAYMENT_METHOD_COD => 'COD',
        self::PAYMENT_METHOD_VNPAY => 'VNPay',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }
}
