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

class Auth2Controller extends Controller
{

    /**
     * 授权
     */
    public function auth()
    {
        $response = $this->wechat->oauth->scopes(['snsapi_userinfo'])->redirect();

        return $response;
    }

    /**
     * 授权回调
     */
    public function callBack()
    {
        $oauth = $this->wechat->oauth;

        $user = $oauth->user();

        Log::error(__METHOD__ . __LINE__, [
            'user' => $user
        ]);

//        $targetUrl = empty($_SESSION['target_url']) ? '/' : $_SESSION['target_url'];
//
//        header('location:'. $targetUrl); // 跳转到 user/profile
    }

}

