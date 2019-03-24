<?php
namespace App\Http\Controllers\Wechat;
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/3/5
 * Time: 17:27
 */


use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    /**
     * 统一下单
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create()
    {
        try{
            $result = $this->wechat->order->unify([
                'body' => '腾讯充值中心-QQ会员充值',
                'out_trade_no' => '20190106125346',
                'total_fee' => 88,
                'notify_url' => '/api/client/notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'trade_type' => 'JSAPI',
                'openid' => 'oGQOas-g9ffRgR0SarLqFxuBgnNw',
            ]);

            Log::error(__METHOD__ . __LINE__, [
                'result' => $result
            ]);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . __LINE__, [
                'msg' => $e->getMessage()
            ]);
        }

        return false;
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

