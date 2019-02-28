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

class Category extends Model
{
    protected $table = 'pictures_category';

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
