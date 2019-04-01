<?php

namespace App\Entities\Pictures\Repository;

use Cboy868\Repositories\Eloquent\SoftDeleteRepository;

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:28
 */

class UserRepository extends SoftDeleteRepository
{
    function model()
    {
        return 'App\Entities\Pictures\User';
    }

    /**
     * 为用户分配picture
     */
    public function givePicture($user_id, $pictures_id)
    {
        $user = $this->with('pictures')->find($user_id);

        $dtime = date('Y-m-d H:i:s');

        $user->pictures()->attach($pictures_id, ['created_at' => $dtime, 'updated_at'=>$dtime]);
    }
}