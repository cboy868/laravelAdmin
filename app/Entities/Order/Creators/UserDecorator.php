<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 22:14
 */

namespace App\Entities\Order\Creators;

use App\Common\ApiStatus;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Validation\UnauthorizedException;

class UserDecorator extends DecoratorAbs
{
    public function __construct(ComponentInterface $componnet)
    {

        parent::__construct($componnet);

        //以下为测试代码

//        $this->user = auth('member')->user();
//
//        if (!$this->user) {
//            throw new UnauthorizedException("授权失败，请先登录", ApiStatus::CODE_2002);
//        }

    }

    public function getUser()
    {
        return $this->user;
    }

    public function filter(): bool
    {
//        if (!$this->orderComponnet->filter()) {
//            return false;
//        }
//
//        $this->user = auth('member')->user();

        return true;
    }

    public function getData(): array
    {
        $oriData = $this->orderComponnet->getData();

        return array_merge([
            'user'=>[
                'model' => $this->getUser()
            ]
        ], $oriData);
    }

    public function create(): bool
    {
//        if (!$this->orderComponnet->create()) {
//            return false;
//        }

        return true;
    }
}