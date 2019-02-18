<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/18
 * Time: 10:38
 */

namespace App\Entities\Novel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NovelContent extends Model
{
    protected $table = 'novel_content';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'novel_id',
        'chapter',
        'page',
        'title',
        'content'
    ];


    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class, 'novel_id');
    }

}