<?php

namespace App\Entities\Order\Repository;

use App\Entities\Order\Creators\GoodsDecorator;
use App\Entities\Order\Creators\OrderCreater;
use App\Entities\Order\Creators\UserDecorator;
use App\Entities\Order\Helper;
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

    public function create(array $data)
    {
        $container = app();

        $order = $container->make(OrderCreater::class);

        $order = $container->make(GoodsDecorator::class, ['componnet'=>$order,'goods_id'=>1,'num'=>1]);

        $order = $container->make(UserDecorator::class, ['componnet'=>$order]);

        return $order;
    }
}