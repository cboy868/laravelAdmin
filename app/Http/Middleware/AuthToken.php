<?php

namespace App\Http\Middleware;

use App\Common\ApiStatus;
use App\Traits\ApiResponse;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthToken
{

    use ApiResponse;
    /**
     * 过来请求的token如果过期，则重新生成新的token
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        $newToken = null;

        //不存在 则异常
        if (!$request->header('authorization')) {
            return $this->failed(ApiStatus::CODE_2004);
        }

        $auth = JWTAuth::parseToken();

        if (!$token = $auth->setRequest($request)->getToken()) {
            return $this->failed(ApiStatus::CODE_2004);
        }

        try {
            $user = $auth->authenticate($token);
            if (!$user) {
                return $this->failed(ApiStatus::CODE_2002);
            }
            $request->headers->set('Authorization', 'Bearer ' . $token);

        } catch (TokenExpiredException $e) {

            try {
                $newToken = JWTAuth::refresh($token);
                // 给当前的请求设置新的token,以备在本次请求中需要调用用户信息
                $request->headers->set('Authorization', 'Bearer ' . $newToken);
            } catch (JWTException $e) {
                // 过期用户
                return $this->failed(ApiStatus::CODE_2003);
            }

        } catch (JWTException $e) {
            return $this->failed(ApiStatus::CODE_2002);
        }
        $response = $next($request);

        if ($newToken) {
            $response->headers->set('Authorization', 'Bearer ' . $newToken);
        }
        return $response;
    }
}
