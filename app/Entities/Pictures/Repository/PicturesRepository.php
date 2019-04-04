<?php

namespace App\Entities\Pictures\Repository;

use App\Entities\Pictures\Pictures;
use Cboy868\Repositories\Eloquent\SoftDeleteRepository;

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

    /**
     * 收藏
     * @param $user
     * @param $id
     * @return mixed
     */
    public function favorite($user, $id)
    {
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
        return $user->unfavorite($id, Pictures::class);
    }
}