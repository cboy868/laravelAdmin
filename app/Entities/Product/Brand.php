<?php

namespace App\Entities\Product;

use App\Entities\ActiveRecord;
use Encore\Admin\Form\Field\HasMany;

class Brand extends ActiveRecord
{
    protected $table = 'product_brand';

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
