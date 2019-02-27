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

class Category extends Model
{
    protected $table = 'goods_category';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'pid',
        'type_id',
        'level',
        'code',
        'name',
        'sort',
        'is_leaf',
        'is_show',
        'thumb',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'deleted_at',
        'created_at',
        'updated_at'
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
     * 取分类下的品牌
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function brands()
    {
        return $this->belongsToMany(Brand::class ,
            'goods_category_brand' ,
            'brand_id' ,
            'cid');
    }

}