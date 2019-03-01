<?php

namespace App\Http\Controllers\Member\V1;

use App\Common\ApiStatus;
use App\Http\Requests\AuthSmsCodeRequest;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends MemberController
{
    /**
     * Create a new AuthController instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:member', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(\App\Http\Requests\AuthRequest $authRequest)
    {

        $params = $authRequest->input('params');

        try {
            if (! $token = auth('member')->attempt($params)) {
                return $this->failed(ApiStatus::CODE_2001);
            }
            return $this->respondWithToken($token);
        } catch (JWTException $e) {
            Log::error(ApiStatus::CODE_2001 . __METHOD__ . __LINE__,[
                'code' => $e->getCode(),
                'msg' => $e->getMessage()
            ]);
            return $this->failed(ApiStatus::CODE_2001, "登录名或密码不匹配", 401);
        }
    }


    /**
     * 手机验证码登录
     * @param AuthSmsCodeRequest $authRequest
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function loginBySmsCode(AuthSmsCodeRequest $authRequest)
    {
        $params = $authRequest->input();


    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->success(['info'=>auth('member')->user()]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try{
            auth('member')->logout();
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
        return $this->respondWithToken(auth('member')->refresh());
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
        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('member')->factory()->getTTL() * 60
        ]);
    }
}
