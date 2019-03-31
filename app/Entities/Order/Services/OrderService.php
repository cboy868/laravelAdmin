<?php

namespace App\Entities\Order\Services;

use App\Common\ApiStatus;
use App\Entities\Order\Creators\ComponentOrder;
use App\Entities\Order\Creators\GoodsDecorator;
use App\Entities\Order\Creators\UserDecorator;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

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

            $user = User::find(1);
            if (!$user) {
                throw new UnauthorizedException("请先登录", ApiStatus::CODE_2002);
            }

            $container = app();

            $orderComponent = $container->make(ComponentOrder::class, ['user'=>$user, 'goodsParams'=>[['id'=>1, 'num'=>1], ['id'=>2, 'num'=>1]]]);

            $orderComponent = $container->make(UserDecorator::class, ['componnet'=>$orderComponent]);

            $orderComponent = $container->make(GoodsDecorator::class, ['componnet'=>$orderComponent]);

            if ($orderComponent->filter()) {
                dd($orderComponent->create());
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