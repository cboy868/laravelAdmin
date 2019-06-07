<?php

namespace App\Entities\Product;

use App\Entities\ActiveRecord;
use App\Entities\Store\Store;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sku extends ActiveRecord
{
    protected $table = 'product_sku';

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
