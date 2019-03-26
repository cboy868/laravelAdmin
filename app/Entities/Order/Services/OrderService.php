<?php

namespace App\Entities\Order\Services;

use App\Entities\Order\Creators\GoodsDecorator;
use App\Entities\Order\Creators\OrderCreater;
use App\Entities\Order\Creators\UserDecorator;
use Illuminate\Support\Facades\Log;

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:28
 */

class OrderService
{

    public function create(array $data)
    {

        try {

//            $user = auth('member')->user();
//
//            if (!$user) {
//                throw new UnauthorizedException("请先登录", ApiStatus::CODE_2002);
//            }

            $container = app();

            $orderComponent = $container->make(OrderCreater::class);

            $orderComponent = $container->make(UserDecorator::class, ['componnet'=>$orderComponent]);

            $orderComponent = $container->make(GoodsDecorator::class, ['componnet'=>$orderComponent,'goods_id'=>1,'num'=>1]);

            if ($orderComponent->filter()) {
                $orderComponent->create();
            }

        } catch (\Exception $e) {
            Log::error(__METHOD__ . __LINE__, [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'msg' => $e->getMessage()
            ]);

            return false;
        }

        return true;
    }
}