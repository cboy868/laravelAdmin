<?php

namespace App\Entities\Product;

use App\Entities\ActiveRecord;
use App\Traits\Tree;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeCategory extends ActiveRecord
{
    use Tree;//树状结构
    use SoftDeletes;

    protected $table = 'product_attribute_category';


    protected $fillable = [
        'pid',
        'name',
        'level',
        'code',
        'intro'
    ];

    /**
     * 获取分类下所有属性
     * @return HasMany
     */
    public function attributeKeys(): HasMany
    {
        return $this->hasMany(AttributeKey::class, 'attribute_category_id');
    }

    public function categorys(): HasMany
    {
        return $this->hasMany(Category::class, 'attribute_category_id');
    }
}
