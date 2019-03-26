<?php

namespace App\Entities\Order\Repository;

use App\Entities\Order\Creators\GoodsDecorator;
use App\Entities\Order\Creators\OrderCreater;
use App\Entities\Order\Creators\UserDecorator;
use App\Entities\Order\Helper;
use Cboy868\Repositories\Eloquent\SoftDeleteRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        DB::beginTransaction();
        try {
            $container = app();

            //订单
            $order = $container->make(OrderCreater::class);

            //商品
            $order = $container->make(GoodsDecorator::class, ['componnet'=>$order,'goods_id'=>1,'num'=>1]);

            //用户
            $order = $container->make(UserDecorator::class, ['componnet'=>$order]);

            DB::commit();

        } catch (\Exception $e) {
            Log::error(__METHOD__ . __LINE__, [
                'code' => $e->getCode(),
                'msg' => $e->getMessage()
            ]);

            DB::rollback();

            return false;
        }

        return $order;
    }
}