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
            return $this->wechat->server->serve();
        } catch (\Exception $e) {
            Log::error(__METHOD__ . __LINE__, [
                'msg' => $e->getMessage()
            ]);
        }
    }
}

