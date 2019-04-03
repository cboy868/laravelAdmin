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

class Cartoon extends Model
{
    use SoftDeletes;
    use scopeModel;

    protected $table = 'pictures_cartoon';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'pictures_id',
        'title',
        "subtitle",
        "thumb",
        "intro",
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
        return $this->hasMany(PicturesItem::class, 'cartoon_id')
            ->orderBy('sort', 'ASC')
            ->orderBy('id', 'DESC');
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
