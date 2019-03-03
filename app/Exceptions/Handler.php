<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Response;
use App\Common\ApiStatus;
use Psr\Log\LoggerInterface;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        $code = $exception->getCode();
        $msg = $exception->getMessage();
        $log = '文件:'.$exception->getFile().
            '，行数:'.$exception->getLine() .
            ',错误码:'.$code.
            ',错误提示:'.$msg;

        try {
            $logger = $this->container->make(LoggerInterface::class);
        } catch (Exception $ex) {
            throw $exception;
        }
        $logger->error($log);

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $requestUri = $request->getRequestUri();

        //如果是接口访问
        if (strstr($requestUri, 'api')) {

            $code = $exception->getCode();
            $msg = $exception->getMessage();

            if (method_exists($exception, 'errors')) {
                Log::error($exception->errors());
            }
            return Response::json(
                [
                    'code' => $code ? $code : ApiStatus::CODE_1099,
                    'msg'  => $msg,
                    'status' => 'error',
                    'data' => []
                ]);
        }

        return parent::render($request, $exception);
    }


    /**
     * 授权失败时，反回对应错误码
     * @param \Illuminate\Http\Request $request
     * @param AuthenticationException $exception
     * @return \Illuminate\Http\Response|mixed
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        /**
         * 只采用以下方法即可,全部走接口形式
         * 不加判断Accept:application/json
         */
        return $this->setStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED)
                ->setStatusMsg('error')
                ->failed(ApiStatus::CODE_2002);

        /**
         * 这里的判断，前端需要设置[{"key":"Accept","value":"application/json","description":"","enabled":true}]
         * 即Accept:application/json
         * 本系统不需要页面上的操作，所以以下暂时注掉
         */
//        if ($request->expectsJson()) {
//            return $this->setStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED)
//                ->setStatusMsg('error')
//                ->failed(ApiStatus::CODE_2002);
//        }
//        return redirect()->guest(route('login'));
    }
}
