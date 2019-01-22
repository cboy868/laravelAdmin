<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/22
 * Time: 12:39
 */

namespace App\Models;


use App\Concern\Likeable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use Likeable;

    protected $table = 'post';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'title',
        'content',
        'posted_at',
        'thumbnail_id',
        'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'posted_at'
    ];

    /**
     * Return the post's author
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopeSearch(Builder $query, ?string $title)
    {
        if ($title) {
            return $query->where('title', 'LIKE', "%{$title}%");
        }
    }

}