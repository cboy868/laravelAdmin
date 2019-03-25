<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 22:14
 */

namespace App\Entities\Order\Creators;

class GoodsDecorator implements CreaterInterface
{
    private $orderComponnet;

    public function __construct($componnet)
    {
        $this->orderComponnet = $componnet;
    }

    public function filter(): bool
    {
        if (!$this->orderComponnet->filter()) {
            return false;
        }

        return true;
    }

    public function getData(): array
    {
        echo __METHOD__;

        return [];
        // TODO: Implement getData() method.
    }

    public function create(): bool
    {
        // TODO: Implement create() method.
    }
}