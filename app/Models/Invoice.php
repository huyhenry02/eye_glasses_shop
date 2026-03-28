<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'invoice_code',
        'customer_name',
        'customer_phone',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'payment_time',
        'payment_transaction_id',
        'payment_bank_code',
        'payment_response_code',
        'payment_secure_hash',
        'employee_id',
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'payment_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_DRAFT => 'Nháp',
        self::STATUS_COMPLETED => 'Hoàn thành',
        self::STATUS_CANCELLED => 'Đã hủy',
    ];

    public const PAYMENT_METHOD_CASH = 'cash';
    public const PAYMENT_METHOD_VNPAY = 'vnpay';

    public const PAYMENT_METHODS = [
        self::PAYMENT_METHOD_CASH => 'Tiền mặt',
        self::PAYMENT_METHOD_VNPAY => 'VNPay',
    ];

    public const PAYMENT_STATUS_UNPAID = 'unpaid';
    public const PAYMENT_STATUS_PAID = 'paid';
    public const PAYMENT_STATUS_FAILED = 'failed';

    public const PAYMENT_STATUSES = [
        self::PAYMENT_STATUS_UNPAID => 'Chưa thanh toán',
        self::PAYMENT_STATUS_PAID => 'Đã thanh toán',
        self::PAYMENT_STATUS_FAILED => 'Thanh toán thất bại',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function invoiceDetails(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }
}
