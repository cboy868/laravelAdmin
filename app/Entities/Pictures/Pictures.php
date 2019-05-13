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
use Overtrue\LaravelFollow\Traits\CanBeFavorited;

class Pictures extends Model
{
    use SoftDeletes;
    use scopeModel;
    use CanBeFavorited;

    const TYPE_ALBUM = 1;//图
    const TYPE_CARTOON = 2;//漫

    protected $table = 'pictures';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'type',
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
        "flag",
        'deleted_at',
        'created_at',
        'updated_at'
    ];


    /**
     * pictures
     * @return HasMany
     */
    public function cartoons(): HasMany
    {
        return $this->hasMany(Cartoon::class, 'pictures_id')
            ->orderBy('chapter', 'ASC')
            ->orderBy('id', 'ASC');
    }

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

    /**
     * 图册封面
     * @return BelongsTo
     */
    public function cover(): BelongsTo
    {
        return $this->belongsTo(PicturesItem::class, 'thumb', 'id');
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

    public function getPrice()
    {
        return $this->type == self::TYPE_CARTOON ?
            config('blog.cartoon_price') : config('blog.pictures_price');
    }

}
