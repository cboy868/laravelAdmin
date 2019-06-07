<?php

namespace App\Entities\Product;

use App\Entities\ActiveRecord;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeKey extends ActiveRecord
{
    protected $table = 'product_attribute_key';

    /**
     * 所属属性分类
     * @return BelongsTo
     */
    public function attributeCategory(): BelongsTo
    {
        return $this->belongsTo(AttributeCategory::class, 'attribute_category_id');
    }


    public function attributeValues(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }


    public function attributeRels(): HasMany
    {
        return $this->hasMany(AttributeRel::class, 'attribute_id');
    }
}
