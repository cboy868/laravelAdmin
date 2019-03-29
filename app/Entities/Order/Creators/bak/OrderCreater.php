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

class OrderCreater implements CreaterInterface
{
    protected $orderRepository;

    private $orderId = 0;

    private $orderNo;

    protected $user;

    protected $components = [];

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderNo = Helper::createOrderNo();

        $this->orderRepository = $orderRepository;
    }

    public function setComponents(String $name,CreaterInterface $component)
    {
        $this->components[$name] = $component;
    }

    public function getComponents(String $name)
    {
        return $this->components[$name];
    }

    public function getOrderCreater() : CreaterInterface
    {
        return $this;
    }

    public function filter(): bool
    {
        return true;
    }

    public function getData(): array
    {
        return [
            'order' => [
                'order_id' => $this->orderId,
                'order_no' => $this->orderNo
            ]
        ];
    }

    public function create(): bool
    {

        $goodsDecoator = $this->getComponents('goodsDecorator');

        $userDecoator = $this->getComponents('userDecorator');

        $goodsData = $goodsDecoator->getData();

        $userData = $userDecoator->getData();

        $dbData = [
            'order_no' => $this->orderNo,
            'user_id'  => $userData['user']['model']->id,
            'title' => $goodsData['goods']['model']->name,
            'price' => $goodsData['goods']['total_price'],
            'origin_price' => $goodsData['goods']['total_price'],
            'type' => 1,
        ];

        if ($model = $this->orderRepository->create($dbData)) {
            $this->orderId = $model->id;
            return true;
        }

        return false;

    }
}