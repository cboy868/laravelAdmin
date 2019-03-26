<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 22:14
 */

namespace App\Entities\Order\Creators;

class UserDecorator implements CreaterInterface
{
    private $orderComponnet;

    protected $user;

    public function __construct($componnet)
    {
        $this->orderComponnet = $componnet;
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