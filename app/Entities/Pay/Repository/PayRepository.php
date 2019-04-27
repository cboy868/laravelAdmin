<?php

namespace App\Entities\Pay\Repository;

use Cboy868\Repositories\Eloquent\SoftDeleteRepository;

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:28
 */

class PayRepository extends SoftDeleteRepository
{

    function model()
    {
        return 'App\Entities\Pay\Pay';
    }

}