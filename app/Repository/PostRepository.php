<?php

namespace App\Repository;

use Cboy868\Repositories\Eloquent\Repository;
use Cboy868\Repositories\Eloquent\SoftDeleteRepository;

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:28
 */

class PostRepository extends SoftDeleteRepository
{
    function model()
    {
        return 'App\Models\Post';
    }
}