<?php

namespace App\Http\Controllers;

use App\Common\ApiStatus;
use App\Entities\Sms\Services\SmsInterface;
use App\Events\UserRegister;
use App\Http\Requests\SmsCodeRequest;
use App\Repository\UserRepository;
use Illuminate\Session\Store as Session;
use Illuminate\Hashing\BcryptHasher as Hasher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Entities\Sms\Services\Code;

class ShowSmsCode extends ApiController
{
    public $model;
    public $session;
    public $hasher;
    public $str;
    public $code;

    public function __construct(UserRepository $user, Session $session, Hasher $hasher, Str $str, Code $code)
    {
        $this->model = $user;
        $this->session = $session;
        $this->hasher = $hasher;
        $this->str = $str;
        $this->code = $code;

    }

    /**
     * //生成短信验证码
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(SmsCodeRequest $request, SmsInterface $sms)
    {
        $mobile = $request->input('mobile');

        Log::error(__METHOD__, [
            'params' => $request->input()
        ]);


        if ($code = $this->generateCode($mobile)) {
            $sms->sendSms($mobile, ['code'=>$code]);
            return $this->respond();
        }

        return $this->failed(ApiStatus::CODE_2051);
    }


    /**
     * 生成用户  并发送验证码
     * @param $mobile
     * @return bool|string
     */
    private function generateCode($mobile)
    {
        try {
            //根据验证码查找用户,如果没有则新生成
            $model = $this->model->findBy('mobile', $mobile);
            if (!$model) {
                $model = $this->model->create([
                    'name' => $mobile,
                    'mobile' => $mobile
                ]);
                //注册事件
                event(new UserRegister($model));
            }

            $code = $this->code->generateCode($mobile);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . __LINE__, [
                'msg' => $e->getMessage()
            ]);

            return false;
        }

        return $code;
    }
}
