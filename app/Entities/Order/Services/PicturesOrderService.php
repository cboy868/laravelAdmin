<?php

namespace App\Entities\Order\Services;

use App\Common\ApiStatus;
use App\Entities\Order\Creators\PayDecorator;
use App\Entities\Order\Creators\Pictures\ComponentOrder;
use App\Entities\Order\Creators\Pictures\PicturesDecorator;
use App\Entities\Order\Creators\UserDecorator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:28
 */

class PicturesOrderService
{

    /**
     * $data 下单数据,结构如下
     *
     * 未来可能要添加sku数据
     *
     * 单商品
     * [
     *  goods_no => 1//商品id
     *  num => 3//商品数量
     * ]
     *
     * 或者
     *
     * 多商品
     *
     * [
     *  [
     *    goods_no => 1, //商品id
     *    num => 2 //商品数量
     *  ],
     *  [
     *    goods_no => 2,
     *    num => 3
     *  ]
     *  ...
     * ]
     *
     *
     * @param array $data
     * @return bool
     */
    public function create(array $data)
    {

        $params = [];

        if (isset($data['goods_no'])) {
            $params = [$data];
        } else if (is_array($data[0])) {
            $params = $data;
        }

        $user = auth('member')->user();

        if (!$user) {
            throw new UnauthorizedException("请先登录", ApiStatus::CODE_2002);
        }


        DB::beginTransaction();

        try {

            $container = app();

            $orderComponent = $container->make(ComponentOrder::class, ['user'=>$user, 'goodsParams'=>$params]);

            $orderComponent = $container->make(UserDecorator::class, ['componnet'=>$orderComponent]);

            $orderComponent = $container->make(PicturesDecorator::class, ['componnet'=>$orderComponent]);

            $orderComponent = $container->make(PayDecorator::class, ['componnet'=>$orderComponent]);

            if ($orderComponent->filter()) {
                $orderComponent->create();
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . __LINE__, [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'msg' => $e->getMessage()
            ]);
            return false;
        }

        return $orderComponent->getData();
    }
}