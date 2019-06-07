<?php

namespace App\Entities\Product;

use App\Entities\ActiveRecord;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeRel extends ActiveRecord
{
    protected $table = 'product_attribute_rel';


    /**
     * 所属属性key
     * @return BelongsTo
     */
    public function attributeKey(): BelongsTo
    {
        return $this->belongsTo(AttributeKey::class, 'attribute_id');
    }

    /**
     * 所属商品
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * 所属sku
     * @return BelongsTo
     */
    public function sku(): BelongsTo
    {
        return $this->belongsTo(Sku::class, 'sku_id');
    }

    /**
     * 所属attributeValue
     * @return BelongsTo
     */
    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}
