<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/18
 * Time: 10:38
 */

namespace App\Entities\Pictures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'pictures_category';

    const TYPE_FREE = 0;//免费的
    const TYPE_PAY = 1;//普通的，需要付费

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        "name",
        "type",
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * pictures
     * @return HasMany
     */
    public function pictures(): HasMany
    {
        return $this->hasMany(Pictures::class, 'category_id');
    }


}
