<?php
namespace App\Http\Controllers\Wechat;
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/3/5
 * Time: 17:27
 */


use App\Common\ApiStatus;
use App\Entities\Order\Repository\OrderRepository;
use App\Entities\Pay\Repository\PayRepository;
use App\Entities\Wechat\Repository\WechatUserRepository;
use App\Entities\Wechat\Services\PayService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use \App\Traits\ApiResponse;

    /**
     * 统一下单
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pay(
        PayRepository $payRepository,
        OrderRepository $orderRepository,
        PayService $payService,WechatUserRepository $wechatUserRepository
        )
    {

        $order_no = \request()->input('order_no');

        $order = $orderRepository->where(['order_no'=>$order_no])->first();

        DB::beginTransaction();

        try {
            $payModel = $payRepository->getActivePayment($order, 'MWEB');

            if (!$payModel) {
                throw new \Exception("创建支付单失败");
            }

            $result = $payService->create($payModel);

            if (!$result) {
                throw new \Exception('微信下单失败');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . __LINE__, [
                'msg' => $e->getMessage()
            ]);
            return $this->failed(ApiStatus::CODE_4005);
        }

        return $this->respond(['mweb_url'=> $result['mweb_url']]);
    }


    /**
     * 支付结果通知网址
     */
    public function notify(Request $request)
    {
        $params = $request->input();

        Log::error(__METHOD__ . __LINE__, [
            'params' => $params
        ]);
    }

}

