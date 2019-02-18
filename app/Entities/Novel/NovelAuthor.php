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
use Illuminate\Database\Eloquent\Relations\HasMany;


class NovelAuthor extends Model
{
    protected $table = 'novel_author';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'summary',
        'quantity',
        'created_at',
        'updated_at'
    ];


    /**
     * 内容
     * @return HasMany
     */
    public function novels(): HasMany
    {
        return $this->hasMany(Novel::class, 'author_id');
    }

}