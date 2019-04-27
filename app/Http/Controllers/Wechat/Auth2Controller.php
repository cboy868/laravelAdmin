<?php
namespace App\Http\Controllers\Wechat;
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/3/5
 * Time: 17:27
 */
use App\Entities\Wechat\Repository\WechatUserRepository;
use Illuminate\Support\Facades\Log;

class Auth2Controller extends Controller
{

    protected $wechatUser;

    public function __construct(WechatUserRepository $wechatUserRepository)
    {
        $this->wechatUser = $wechatUserRepository;
        parent::__construct();
    }


    /**
     * 授权
     */
    public function auth()
    {
        return $this->wechat->oauth
            ->scopes(['snsapi_userinfo'])
            ->setRequest(request())
            ->redirect();
    }

    /**
     * 授权回调
     */
    public function callBack()
    {

        Log::error(__METHOD__, [
            'a' => request()->input()
        ]);

        $oauth = $this->wechat->oauth;

        $user = $oauth->user();

        $original = $user->getOriginal();

        $model = $this->wechatUser->create([
            'unionid' => isset($original['unionid']) ?? '',
            'openid' => $user->getId(),
            'nickname' => $user->getNickname(),
            'sex'=> $original['sex'],
            'language' => $original['language'],
            'province'=> $original['province'],
            'country' => $original['country'],
            'headimgurl' => $user->getAvatar(),
        ]);

        Log::error(__METHOD__ . __LINE__, [
            'user' => $user,
            'model' => $model
        ]);

//        $targetUrl = empty($_SESSION['target_url']) ? '/' : $_SESSION['target_url'];
//
//        header('location:'. $targetUrl); // 跳转到 user/profile
    }

}

