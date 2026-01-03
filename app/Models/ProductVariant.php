<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'color_id',
        'size_id',
        'price',
        'special_price',
        'stock',
        'sku',
        'shop_sku',
        'availability'
    ];

    protected $casts = [
        'availability' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }


    public function images()
    {
        return $this->hasMany(VariantImage::class);
    }
}
