<?php

namespace App\Entities\Product;

use App\Entities\ActiveRecord;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends ActiveRecord
{
    protected $table = 'product_category';

    /**
     * 所属属性分类
     * @return BelongsTo
     */
    public function attributeCategory(): BelongsTo
    {
        return $this->belongsTo(AttributeCategory::class, 'attribute_category_id');
    }


    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
