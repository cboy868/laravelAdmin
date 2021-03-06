<?php

namespace App\Entities\Wechat\Repository;

use Cboy868\Repositories\Eloquent\SoftDeleteRepository;

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:28
 */

class WechatUserRepository extends SoftDeleteRepository
{
    function model()
    {
        return 'App\Entities\Wechat\WechatUser';
    }

    /**
     * 检查是否已有对应微信openid
     */
    public function checkAuth()
    {
        $user = auth('member')->user();

        if (!$user) return false;

        $wechat = $this->where(['user_id'=>$user->id])
            ->orderBy('id', 'DESC')
            ->first();


        if ($wechat && $wechat->openid) {
            return true;
        }

        return false;
    }
}