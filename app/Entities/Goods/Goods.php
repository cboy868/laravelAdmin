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

class Goods extends Model
{
    protected $table = 'goods';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'cid',
        'brand_id',
        'pinyin',
        'goods_no',
        'name',
        'subtitle',
        'thumb',
        'keywords',
        'description',
        'content',
        'unit',
        'sort',
        'min_price',
        'max_price',
        'status',
        'is_recommend',
        'is_show',
        'deleted_at',
        'created_at',
        'updated_at'
    ];


    /**
     * 所属分类
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'cid');
    }

    /**
     * 所属品牌
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

}