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

class Sku extends Model
{
    protected $table = 'goods_sku';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'goods_id',
        'sku_no',
        'quantity',
        'price',
        'original_price',
        'title',
        'key_value',
        'deleted_at',
        'created_at',
        'updated_at'
    ];


    /**
     * 所属商品
     * @return BelongsTo
     */
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }

}