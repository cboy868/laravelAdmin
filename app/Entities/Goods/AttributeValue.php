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

class AttributeValue extends Model
{
    protected $table = 'goods_attribute_value';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'type_id',
        'attr_id',
        'value',
        'thumb',
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
     * 所属属性
     * @return BelongsTo
     */
    public function key(): BelongsTo
    {
        return $this->belongsTo(AttributeKey::class, 'attr_id');
    }
}