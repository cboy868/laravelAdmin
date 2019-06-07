<?php

namespace App\Entities\Product;

use App\Entities\ActiveRecord;
use App\Entities\Store\Store;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends ActiveRecord
{
    protected $table = 'product';


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function attributeRels(): HasMany
    {
        return $this->hasMany(AttributeRel::class, 'product_id');
    }

    public function skus(): HasMany
    {
        return $this->hasMany(Sku::class, 'product_id');
    }
}
