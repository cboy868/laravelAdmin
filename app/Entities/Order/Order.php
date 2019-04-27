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
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'order_info';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        "id",
        "order_no",
        "user_id",
        "title",
        "price",
        "origin_price",
        "type",
        "progress",
        "note",
        "deleted_at",
        "created_at",
        "updated_at",
    ];

    /**
     * pictures
     * @return HasMany
     */
    public function goods(): HasMany
    {
        return $this->hasMany(OrderGoods::class, 'order_id')
            ->orderBy('id', 'DESC');
    }
}
