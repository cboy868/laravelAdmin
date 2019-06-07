<?php

namespace App\Entities\Product;

use App\Entities\ActiveRecord;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeValue extends ActiveRecord
{
    protected $table = 'product_attribute_value';

    /**
     * 所属属性
     * @return BelongsTo
     */
    public function attributeKey(): BelongsTo
    {
        return $this->belongsTo(AttributeKey::class, 'attribute_id');
    }


    public function attributeRels(): HasMany
    {
        return $this->hasMany(AttributeRel::class, 'attribute_value_id');
    }
}
