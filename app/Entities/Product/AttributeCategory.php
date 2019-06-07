<?php

namespace App\Entities\Product;

use App\Entities\ActiveRecord;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeCategory extends ActiveRecord
{
    protected $table = 'product_attribute_category';

    /**
     * 获取分类下所有属性
     * @return HasMany
     */
    public function attributeKeys(): HasMany
    {
        return $this->hasMany(AttributeKey::class, 'attribute_category_id');
    }

    public function categorys(): HasMany
    {
        return $this->hasMany(Category::class, 'attribute_category_id');
    }
}
