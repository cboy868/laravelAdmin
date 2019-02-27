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

class Brand extends Model
{
    protected $table = 'goods_brand';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'cn_name',
        'logo',
        'deleted_at',
        'created_at',
        'updated_at'
    ];


    /**
     * 取品牌有哪些分类
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categorys()
    {
        return $this->belongsToMany(Category::class ,
            'goods_category_brand' , 'cid' , 'brand_id');
    }
}