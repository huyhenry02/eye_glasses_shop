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
    public const GENDER_MALE = 'Nam';
    public const GENDER_FEMALE = 'Nữ';
    public const GENDER_UNISEX = 'Unisex';
    public const GENDERS = [
        self::GENDER_MALE => 'Nam',
        self::GENDER_FEMALE => 'Nữ',
        self::GENDER_UNISEX => 'Unisex',
    ];
    public const SHAPES = [
        'Vuông' => 'Vuông',
        'Tròn' => 'Tròn',
        'Chữ nhật' => 'Chữ nhật',
        'Oval' => 'Oval',
        'Mắt mèo' => 'Mắt mèo',
        'Phi công' => 'Phi công',
    ];

    public const RIM_TYPES = [
        'Full viền' => 'Full viền',
        'Nửa viền' => 'Nửa viền',
        'Không viền' => 'Không viền',
    ];

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'slug',
        'description',
        'brand',
        'frame_material',
        'lens_material',
        'shape',
        'rim_type',
        'gender',
        'frame_color',
        'lens_color',
        'colors',
        'lens_width',
        'bridge_width',
        'temple_length',
        'frame_width',
        'price',
        'discount_price',
        'stock_quantity',
        'image',
        'image_detail_1',
        'image_detail_2',
        'image_detail_3',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'lens_width' => 'integer',
        'bridge_width' => 'integer',
        'temple_length' => 'integer',
        'frame_width' => 'integer',
        'price' => 'integer',
        'discount_price' => 'integer',
        'stock_quantity' => 'integer',
        'is_active' => 'integer',
        'is_featured' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function invoiceDetails(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    public function getFinalPriceAttribute(): int
    {
        return $this->discount_price ?: $this->price;
    }
}
