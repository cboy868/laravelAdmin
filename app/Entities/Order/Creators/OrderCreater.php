<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 22:14
 */

namespace App\Entities\Order\Creators;

use App\Entities\Order\Helper;

class OrderCreater implements CreaterInterface
{
    private $orderNo;

    public function __construct()
    {
        $this->orderNo = Helper::createOrderNo();
    }

    public function filter(): bool
    {
        return true;
    }

    public function getData(): array
    {
        return [
            'order' => [
                'order_no' => $this->orderNo
            ]
        ];
    }

    public function create(): bool
    {
        // TODO: Implement create() method.
    }
}