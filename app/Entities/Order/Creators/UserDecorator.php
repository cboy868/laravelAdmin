<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 22:14
 */

namespace App\Entities\Order\Creators;

use App\Common\ApiStatus;
use App\Repository\UserRepository;
use Illuminate\Validation\UnauthorizedException;

class UserDecorator implements CreaterInterface
{
    private $orderComponnet;

    protected $userRepository;

    protected $user;

    public function __construct(CreaterInterface $componnet)
    {
        $this->orderComponnet = $componnet;

        $this->user = auth('member')->user();

        if (!$this->user) {
            throw new UnauthorizedException("授权失败，请先登录", ApiStatus::CODE_2002);
        }

    }

    public function filter(): bool
    {
        if (!$this->orderComponnet->filter()) {
            return false;
        }

        $this->user = auth('member')->user();

        return true;
    }

    public function getData(): array
    {
        $oriData = $this->orderComponnet->getData();

        return array_merge(['user'=>['id'=>1]], $oriData);
    }

    public function create(): bool
    {
        // TODO: Implement create() method.
    }
}