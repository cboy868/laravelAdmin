<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 22:14
 */

namespace App\Entities\Order\Creators;

use App\Common\ApiStatus;
use App\Entities\Goods\Repository\GoodsRepository;
use App\Entities\Order\Helper;
use App\Entities\Order\Repository\OrderRepository;
use App\Models\User;

class ComponentOrder implements ComponentInterface
{

    protected $orderNo;

    protected $orderId;

    protected $user;

    protected $goodsInfo;

    protected $goodsRepository;

    protected $components = [];


    public function __construct(OrderRepository $orderRepository, User $user, Array $goodsInfo, GoodsRepository $goodsRepository)
    {
        $this->goodsRepository = $goodsRepository;
        $this->orderNo = Helper::createOrderNo();

        $this->user = $user;
        $this->goodsInfo = $goodsInfo;

        $this->parseGoodsInfo();
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
                'order_no' => $this->orderNo,
                'user_id' => $this->user->id,
                'user' => $this->user,
            ],
            'user' => $this->user->toArray(),
            'goods' => $this->parseGoodsInfo()
        ];
    }

    public function create(): bool
    {



        return true;
    }

    public function parseGoodsInfo()
    {
        if (!is_array($this->goodsInfo)) {
            throw new \Exception("数据格式错误". ApiStatus::CODE_1001);
        }

        $goodsIds = array_column($this->goodsInfo, 'id');

        $goodsModels = $this->goodsRepository->whereIn('id', $goodsIds)->get();
        dd($goodsModels);
        dd($goodsIds);

        $data = [];
        foreach ($this->goodsInfo as $goods) {

        }
    }
}