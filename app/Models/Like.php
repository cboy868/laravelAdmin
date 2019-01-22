<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/22
 * Time: 11:47
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;


class Like extends Model
{
    protected $table = 'likes';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'likeable_id',
        'likeable_type',
        'author_id',
    ];

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }


}