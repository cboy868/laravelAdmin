<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/27
 * Time: 13:52
 */

namespace App\Entities\Wechat\Services;

use App\Entities\Order\Repository\OrderRepository;
use App\Entities\Pay\Pay;
use App\Entities\Pay\Repository\PayRepository;
use App\Entities\Pictures\Repository\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayService extends Wechat
{
    protected $payRepository;
    protected $orderRepository;
    protected $userRepository;

    public function __construct(PayRepository $payRepository,
                                OrderRepository $orderRepository,
                                UserRepository $userRepository)
    {
        $this->payRepository = $payRepository;
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * 统一下单
     * @return mixed
     */
    public function create(Pay $payModel, $type = 'MWEB')
    {
        $app = $this->getPayInstance();

        try {
            $result = $app->order->unify([
                'body' => $payModel->title,
                'out_trade_no' => $payModel->local_trade_no,
                'total_fee' => $payModel->total_fee,
                'notify_url' => 'http://' . \request()->getHttpHost() . '/api/client/wechat-order-notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'trade_type' => $type,//h5下单
                "spbill_create_ip" =>get_client_ip()
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


    public function notify()
    {
        $app = $this->getPayInstance();

        $response = $app->handlePaidNotify(function ($message, $fail) {

            Log::error(__METHOD__ . __LINE__, [
                'msg' => $message,
                'fail' => $fail
            ]);

            if ($message['result_code'] != 'SUCCESS') {
                return false;
            }

            DB::beginTransaction();

            try {
                $pay = $this->payRepository->where(['local_trade_no' => $message['out_trade_no']])->first();

                $order = $this->orderRepository->where(['order_no' => $pay->order_no])->first();

                $pay->success($message['total_fee'], $message);
                $order->success();

                $goods = $order->goods;

                $this->userRepository->givePicture($order->user_id, $goods[0]->goods_no);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("wechat_pay_error", [
                    'error' => $fail,
                    'msg' => $message,
                    'exception' => $e->getMessage()
                ]);
                return false;
            }

            return true;
        });

        return $response;
    }
}