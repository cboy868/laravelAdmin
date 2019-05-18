<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/18
 * Time: 10:38
 */

namespace App\Entities\Focus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FocusItem extends Model
{
    protected $table = 'focus_item';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        "fid",
        "path",
        "link",
        "title",
        "intro",
        "sort"
    ];

    public $timestamps = false;

    /**
     * 所属图集
     * @return BelongsTo
     */
    public function focus(): BelongsTo
    {
        return $this->belongsTo(Focus::class, 'fid');
    }
}
