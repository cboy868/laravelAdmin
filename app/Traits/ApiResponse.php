<?php
/**
 * User: wansq
 * Date: 2018/5/4
 * Time: 20:57
 */

namespace App\Traits;

use App\Common\ApiStatus;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Response;

trait ApiResponse
{
    protected $statusCode = FoundationResponse::HTTP_OK; //http状态码

    protected $code = ApiStatus::CODE_0; //程序内部码

    protected $status = 'ok';


    protected $msg;

    /**
     * @param $data
     * @param array $header
     * @return mixed
     */
    public function respond($data=[], $header = [])
    {
        $data = [
            'code' => $this->code,
            'msg'  => $this->getCodeMsg(),
            'status' => $this->status,
            'data' => $data
        ];
        return Response::json($data,$this->getStatusCode(),$header);
    }

    /**
     * @param $code
     * @param string $msg
     * @return mixed
     * 失败返回
     */
    public function failed($code, $msg='', $statusCode=200){
        return $this->setCode($code)
            ->setCodeMsg($msg)
            ->setStatusCode($statusCode)
            ->setStatus('error')
            ->respond([]);
    }

    /**
     * @return int
     * http状态码
     */
    private function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     * 获取错误信息
     */
    private function getCodeMsg()
    {
        if ($this->msg) {
//            if (isset(ApiStatus::$errCodes[$this->code])) {
//                return ApiStatus::$errCodes[$this->code] . ': ' . $this->msg;
//            }
            return $this->msg;
        }

        if (isset(ApiStatus::$errCodes[$this->code])) {
            return ApiStatus::$errCodes[$this->code];
        }

        return '';
    }
    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $code
     * @return $this
     * 设置程序内部状态码
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param $msg
     * @return $this
     * 设置错误信息
     */
    public function setCodeMsg($msg)
    {
        $this->msg = $this->msg ?? $msg;

        return $this;
    }
}