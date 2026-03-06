<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{

    protected $table = 'products';

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;
    public const STATUSES = [
        self::STATUS_ACTIVE => 'Hoạt động',
        self::STATUS_INACTIVE => 'Ngừng hoạt động',
    ];
    public const STYLE_BASIC = 'basic';
    public const STYLE_CASUAL = 'casual';
    public const STYLE_SPORT = 'sport';
    public const STYLE_FORMAL = 'formal';

    public const STYLES = [
        self::STYLE_BASIC => 'Cơ bản',
        self::STYLE_CASUAL => 'Thường ngày',
        self::STYLE_SPORT => 'Thể thao',
        self::STYLE_FORMAL => 'Trang trọng',
    ];
    protected $fillable = [
        'category_id',
        'code',
        'name',
        'slug',
        'description',
        'weight',
        'dimension',
        'material',
        'price',
        'discount_price',
        'stock_quantity',
        'image',
        'image_detail_1',
        'image_detail_2',
        'image_detail_3',
        'colors',
        'is_active',
        'is_featured',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function invoiceDetails(): HasMany{
        return $this->hasMany(InvoiceDetail::class);
    }
}
