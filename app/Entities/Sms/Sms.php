<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/18
 * Time: 10:38
 */

namespace App\Entities\Sms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sms extends Model
{
    protected $table = 'sms';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'mobile',
        'content',
        'send_at',
        'status',
        'created_at',
        'updated_at',
    ];
}