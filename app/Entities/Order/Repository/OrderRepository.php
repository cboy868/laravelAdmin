<?php

namespace App\Entities\Order\Repository;

use Cboy868\Repositories\Eloquent\SoftDeleteRepository;

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:28
 */

class OrderRepository extends SoftDeleteRepository
{

    function model()
    {
        return 'App\Entities\Order\Order';
    }

}