<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/18
 * Time: 10:38
 */

namespace App\Entities\Wechat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WechatUser extends Model
{
    protected $table = 'wechat_user';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'unionid',
        'openid',
        'user_id',
        'nickname',
        'sex',
        'language',
        'province',
        'country',
        'headimgurl',
        'subscribe',
        'subscribe_at',
        'mobile',
        'created_at',
        'updated_at',
    ];
}
