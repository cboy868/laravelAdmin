<?php

namespace App\Entities\Product;

use App\Entities\ActiveRecord;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeGroup extends ActiveRecord
{
    protected $table = 'product_attribute_group';

    /**
     * 所属属性分类
     * @return BelongsTo
     */
    public function attributeCategory(): BelongsTo
    {
        return $this->belongsTo(AttributeCategory::class, 'attribute_category_id');
    }


    public function attributes(): HasMany
    {
        return $this->hasMany(AttributeKey::class, 'attribute_group_id');
    }
}
