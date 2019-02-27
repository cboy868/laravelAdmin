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

class Images extends Model
{
    protected $table = 'goods_images';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'res_name',
        'res_id',
        'title',
        'path',
        'name',
        'ext',
        'sort',
        'intro',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}