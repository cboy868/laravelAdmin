<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/18
 * Time: 10:38
 */

namespace App\Entities\Goods;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeRel extends Model
{
    protected $table = 'goods_attribute_rel';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'type_id',
        'cid',
        'goods_id',
        'attribute_key_id',
        'attribute_value_id',
        'value',
        'deleted_at',
    ];

    /**
     * 所属类型
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    /**
     * 所属分类
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'cid');
    }

    /**
     * 所属产品
     * @return BelongsTo
     */
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    /**
     * 所属属性
     * @return BelongsTo
     */
    public function attrKey(): BelongsTo
    {
        return $this->belongsTo(AttributeKey::class, 'attribute_key_id');
    }

    /**
     * 所属属性值
     * @return BelongsTo
     */
    public function attrValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}