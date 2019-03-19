<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/18
 * Time: 10:38
 */

namespace App\Entities\Pictures;

use App\Traits\scopeModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pictures extends Model
{
    use SoftDeletes;
    use scopeModel;

    protected $table = 'pictures';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'category_id',
        'author',
        "name",
        "thumb",
        "sort",
        "intro",
        "num",
        "views",
        "comms",
        "created_by",
        "recommend",
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * pictures
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(PicturesItem::class, 'pictures_id')
            ->orderBy('sort', 'ASC')
            ->orderBy('id', 'DESC');
    }

    /**
     * 所属分类
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


    public function createdby(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class ,
            'pictures_user_rel' ,
            'pictures_id',
            'user_id');
    }

}
