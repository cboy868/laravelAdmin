<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/27
 * Time: 13:52
 */

namespace App\Entities\Wechat\Services;

use App\Entities\Pay\Pay;
use App\Entities\Pay\Repository\PayRepository;
use Illuminate\Support\Facades\Log;

class PayService extends Wechat {

    /**
     * 统一下单
     * @return mixed
     */
    public function create(Pay $payModel, $type='MWEB')
    {
        $app = $this->getPayInstance();

        try{
            $result = $app->order->unify([
                'body' => $payModel->title,
                'out_trade_no' => $payModel->local_trade_no,
                'total_fee' => $payModel->total_fee,
                'notify_url' => '/api/client/notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'trade_type' => $type,//h5下单
                'openid' => 'o889k529BxLR4vWB10p9I5pWrYVs',
            ]);

            $payModel->trade_no = $result['prepay_id'];
            $payModel->pay_method = $result['trade_type'];
            $payModel->pay_result = PayRepository::RESULT_PROCESS;

            $payModel->save();

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