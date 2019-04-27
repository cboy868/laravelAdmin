<?php

namespace App\Entities\Pay\Repository;

use App\Entities\Order\Helper;
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

    const RESULT_INIT = 1;//失败
    const RESULT_PROCESS = 2;//支付中
    const RESULT_SUCCESS = 3;//成功

    /**
     * 获取有效支付记录，没有则创建
     * @param $order_no
     */
    public function getActivePayment($order, $type)
    {
        $user = auth('member')->user();

        $payModel = $this->where([
            'order_no' => $order->order_no,
            'pay_result' => self::RESULT_INIT,
            'user_id' => $user->id
        ])->first();

        if ($payModel) return $payModel;

        $payModel = $this->where([
            'pay_result' => self::RESULT_PROCESS,
            'pay_method' => $type,
            'order_no' => $order->order_no,
            'user_id' => $user->id
        ])->first();

        if ($payModel) return $payModel;

        $dbData = [
            'title' => $order->title,
            'user_id' => $user->id,
            'order_id' => $order->id,
            'order_no' => $order->order_no,
            'local_trade_no' => Helper::createPayNo(),
            'total_fee' => $order->price,
        ];

        return $this->create($dbData);
    }
}