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
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PicturesItem extends Model
{
    protected $table = 'pictures_item';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        "pictures_id",
        "title",
        "path",
        "name",
        "ext",
        "sort",
        "intro",
        "deleted_at",
        'created_at',
        'updated_at'
    ];

    /**
     * 所属图集
     * @return BelongsTo
     */
    public function picture(): BelongsTo
    {
        return $this->belongsTo(Pictures::class, 'pictures_id');
    }
}
