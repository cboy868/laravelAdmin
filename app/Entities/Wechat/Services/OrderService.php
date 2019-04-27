<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/27
 * Time: 13:52
 */

namespace App\Entities\Wechat\Services;

use Illuminate\Support\Facades\Log;

class OrderService extends Wechat {

    /**
     * 统一下单
     * @return mixed
     */
    public function create()
    {
        $app = $this->getPayInstance();

        try{
            $result = $app->order->unify([
                'body' => '充值',
                'out_trade_no' => '201901061253462',
                'total_fee' => 1,
                'notify_url' => '/api/client/notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'trade_type' => 'MWEB',//h5下单
                'openid' => 'o889k529BxLR4vWB10p9I5pWrYVs',
            ]);

            Log::info(__METHOD__ . __LINE__, [
                'result' => $result
            ]);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . __LINE__, [
                'msg' => $e->getMessage()
            ]);
            return false;
        }

        return $result;

    }
}