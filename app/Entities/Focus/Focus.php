<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/18
 * Time: 10:38
 */

namespace App\Entities\Focus;

use App\Traits\scopeModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Focus extends Model
{
    use scopeModel;

    protected $table = 'focus';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'appid',
        "pos",
        "intro",
    ];

    /**
     * pictures
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(FocusItem::class, 'fid')
            ->orderBy('sort', 'ASC')
            ->orderBy('id', 'DESC');
    }
}
