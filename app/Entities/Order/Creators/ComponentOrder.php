<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 22:14
 */

namespace App\Entities\Order\Creators;

use App\Entities\Order\Helper;
use App\Entities\Order\Repository\OrderRepository;

class ComponentOrder implements ComponentInterface
{
    private $orderNo;

    protected $components = [];

    public function __construct(OrderRepository $orderRepository)
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
//                'order_id' => $this->orderId,
                'order_no' => $this->orderNo
            ]
        ];
    }

    public function create(): bool
    {
        return true;
    }
}