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


class Type extends Model
{
    protected $table = 'goods_type';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

}