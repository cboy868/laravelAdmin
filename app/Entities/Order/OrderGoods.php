<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/18
 * Time: 10:38
 */

namespace App\Entities\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderGoods extends Model
{
    protected $table = 'order_goods';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        "id",
        "order_id",
        "user_id",
        "title",
        "type_id",
        "category_id",
        "goods_no",
        "sku_no",
        "sku_name",
        "price",
        "origin_price",
        "num",
        "use_time",
        "note",
        "is_refund",
        "deleted_at",
        "created_at",
        "updated_at",
    ];


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
