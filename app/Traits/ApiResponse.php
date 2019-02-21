<?php
/**
 * User: wansq
 * Date: 2018/5/4
 * Time: 20:57
 */

namespace App\Traits;

use App\Common\ApiStatus;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Response;

trait ApiResponse
{
    private $code = ApiStatus::CODE_0; //程序内部码

    private $codeMsg;

    private $status = FoundationResponse::HTTP_OK; //http状态码

    private $statusMsg = 'ok';//第三方、等使用的一些错误标识

    private $header=[];

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

    /**
     * 获取系统内部码
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * 设置http状态码
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * 获取http码
     * @param $status
     * @return $this
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * 设置程序状态码
     * @param $status
     * @return $this
     */
    public function setStatusMsg($statusMsg)
    {
        $this->statusMsg = $statusMsg;
        return $this;
    }

    /**
     * 取程序状态码
     * @param $status
     * @return $this
     */
    public function getStatusMsg()
    {
        return $this->statusMsg;
    }

    /**
     * @param $msg
     * @return $this
     * 设置错误信息
     */
    public function setCodeMsg($msg)
    {
        $this->codeMsg = $msg;
        return $this;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getCodeMsg()
    {
        if (!empty($this->codeMsg)) {
            return $this->codeMsg;
        }

        return isset(ApiStatus::$errCodes[$this->code]) ?
            ApiStatus::$errCodes[$this->code]:'';
    }

    /**
     * 设置反回header
     * @param $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * 获取header
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param $data
     * @param array $header
     * @return mixed
     */
    public function respond(array $data=[])
    {
        //如果是资源类型，则再添加code、msg、status
        if ($data instanceof ResourceCollection) {
            return $data->additional([
                'code' => $this->getCode(),
                'msg'  => $this->getCodeMsg(),
                'status' => $this->getStatusMsg(),
            ]);
        }

        return Response::json([
            'code' => $this->getCode(),
            'msg'  => $this->getCodeMsg(),
            'status' => $this->getStatusMsg(),
            'data' => $data
        ],$this->getStatus(),$this->getHeader());
    }

    /**
     * 失败返回
     * @param $code
     * @param string $msg
     * @return mixed
     */
    public function failed($code){
        return $this->setCode($code)->respond([]);
    }

    /**
     * 成功返回
     * @param array $data
     * @return mixed
     */
    public function success($data=[]){
        return $this->respond($data);
    }

}