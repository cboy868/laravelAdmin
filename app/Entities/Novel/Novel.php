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
use Illuminate\Database\Eloquent\Relations\HasMany;

class Novel extends Model
{
    protected $table = 'novel';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'title',
        'category_id',
        'author_id',
        'summary',
        'rate',
        'like',
        'words',
        'type',
        'created_at',
        'updated_at'
    ];


    /**
     * 作者
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(NovelAuthor::class, 'author_id');
    }

    /**
     * 内容
     * @return HasMany
     */
    public function contents(): HasMany
    {
        return $this->hasMany(NovelContent::class, 'novel_id');
    }

}