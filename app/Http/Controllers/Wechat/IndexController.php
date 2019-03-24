<?php
namespace App\Http\Controllers\Wechat;
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/3/5
 * Time: 17:27
 */


use EasyWeChat\Factory;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{

    public function index()
    {
        Log::error(__METHOD__. __LINE__, [
            'a' => request()->all()
        ]);
        try{
            $this->wechat->server->push(function ($message) {
                return "您好！欢迎使用 EasyWeChat";
            });

            return $this->wechat->server->serve();
        } catch (\Exception $e) {
            Log::error(__METHOD__ . __LINE__, [
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function pay()
    {
        $this->wechat->setSubMerchant('1487057712', 'sub-app-id');
    }
}

