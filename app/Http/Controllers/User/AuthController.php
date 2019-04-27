<?php

namespace App\Http\Controllers\User;

use App\Common\ApiStatus;
use App\Events\UserLogin;
use App\Http\Controllers\ApiController;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Cache;
use App\Entities\Sms\Services\Code;

class AuthController extends ApiController
{
    /**
     * Create a new AuthController instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:member', ['except' => ['login', 'loginBySmsCode']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(\App\Http\Requests\AuthRequest $authRequest)
    {

        $params = $authRequest->input();

        Log::error(__METHOD__, $params);

        try {
            if (!$token = auth('member')->attempt($params)) {
                return $this->failed(ApiStatus::CODE_2001);
            }

            event(new UserLogin(auth('member')->user()));

            return $this->respondWithToken($token);
        } catch (JWTException $e) {
            Log::error(ApiStatus::CODE_2001 . __METHOD__ . __LINE__, [
                'code' => $e->getCode(),
                'msg' => $e->getMessage()
            ]);
            return $this->failed(ApiStatus::CODE_2001, "登录名或密码不匹配", 401);
        }
    }


    public function loginBySmsCode(\App\Http\Requests\AuthMemberRequest $authRequest, UserRepository $user, Code $code)
    {

        $params = $authRequest->input();

        try {
            $model = $user->findBy('mobile', $params['mobile']);


            if (!$model) {
                Log::error(__METHOD__ . __LINE__, [
                    'msg' => '未找到用户信息'
                ]);
                return $this->failed(ApiStatus::CODE_2001, "未找到用户信息", 401);
            }

            if (!$code->check($params['mobile'], $params['code'])) {
                return $this->failed(ApiStatus::CODE_2001, "验证码错误或不匹配", 401);
            }
           

            if (!$token = auth('member')->tokenById($model->id)) {
                return $this->failed(ApiStatus::CODE_2001);
            }

            //这里是登录成功 1、修改登录次数 2、如果是第一次登录，则执行第一次登录事件
            //执行一个登录事件就ok

            event(new UserLogin($model));

            return $this->respondWithToken($token);

        } catch (JWTException $e) {

            Log::error(ApiStatus::CODE_2001 . __METHOD__ . __LINE__, [
                'code' => $e->getCode(),
                'msg' => $e->getMessage()
            ]);

            return $this->failed(ApiStatus::CODE_2001, "验证码错误或不匹配", 401);
        }
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth('member')->user();

        if ($user) {
            $userArray = $user->toArray();

            $userArray['level_label'] = $user->getLevel();
            $userArray['show_id'] = $user->id + 8478846;
            return $this->respond($userArray);
        }

        return $this->failed(ApiStatus::CODE_2002);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth('admin')->logout();
            return $this->setCodeMsg("Successfully logged out")->success();
        } catch (\Exception $e) {
            return $this->failed(ApiStatus::CODE_1001);
        }
    }

    /**
     * Refresh a token.
     * 刷新token，如果开启黑名单，以前的token便会失效。
     * 值得注意的是用上面的getToken再获取一次Token并不算做刷新，两次获得的Token是并行的，即两个都可用。
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('admin')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->setHeader(['Authorization' => 'Bearer ' . $token])->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ]);
    }
}
