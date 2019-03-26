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

class UserDecorator implements CreaterInterface
{
    private $orderComponnet;

    protected $user;

    public function __construct(CreaterInterface $componnet)
    {
        $this->orderComponnet = $componnet;

        //以下为测试代码
        $this->user = User::find(1);

//        $this->user = auth('member')->user();
//
//        if (!$this->user) {
//            throw new UnauthorizedException("授权失败，请先登录", ApiStatus::CODE_2002);
//        }

        $this->setComponents();

    }

    public function setComponents()
    {
        $this->getOrderCreater()->setComponents("userDecorator", $this);
    }

    public function getOrderCreater()
    {
        return $this->orderComponnet->getOrderCreater();
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
        if (!$this->orderComponnet->create()) {
            return false;
        }

        return true;
    }
}