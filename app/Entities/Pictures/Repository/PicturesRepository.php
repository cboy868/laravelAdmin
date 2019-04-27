<?php

namespace App\Entities\Pictures\Repository;

use App\Common\ApiStatus;
use App\Entities\Pictures\Cartoon;
use App\Entities\Pictures\Pictures;
use Cboy868\Repositories\Eloquent\SoftDeleteRepository;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:28
 */

class PicturesRepository extends SoftDeleteRepository
{
    const TYPE_ALBUM = 1;//图
    const TYPE_CARTOON = 2;//漫

    function model()
    {
        return 'App\Entities\Pictures\Pictures';
    }


    static public function types($type)
    {
        $types = [
            self::TYPE_ALBUM => Pictures::class,
            self::TYPE_CARTOON => Cartoon::class
        ];

        return isset($types[$type]) ? $types[$type] : false;
    }

    /**
     * 收藏
     * @param $user
     * @param $id
     * @return mixed
     */
    public function favorite($user, $id)
    {
//        $item = self::find($id);
//
//        if (!$item) throw new ResourceNotFoundException('data not found', ApiStatus::CODE_1021);
//
//        if (!self::types($item->type)) throw new \Exception('data type error', ApiStatus::CODE_1022);
//
//        return $user->favorite($id, self::types($item->type));

        //图片和卡通暂时先不分开
        return $user->favorite($id, Pictures::class);
    }

    /**
     * 取消收藏
     * @param $user
     * @param $id
     * @return mixed
     */
    public function unFavorite($user, $id)
    {
//        $item = self::find($id);
//
//        if (!$item) throw new ResourceNotFoundException('data not found', ApiStatus::CODE_1021);
//
//        if (!self::types($item->type)) throw new \Exception('data type error', ApiStatus::CODE_1022);
//
//        return $user->unfavorite($id, self::types($item->type));

        //图片和卡通暂时先不分开
        return $user->unfavorite($id, Pictures::class);
    }
}