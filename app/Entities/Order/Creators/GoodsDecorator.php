<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 22:14
 */

namespace App\Entities\Order\Creators;

use App\Entities\Goods\Repository\GoodsRepository;
use App\Entities\Order\Repository\OrderGoodsRepository;

class GoodsDecorator extends DecoratorAbs
{
    protected $orderGoodsRepository;

    protected $goodsRepository;

    protected $goods_id;

    protected $goods_num;

    protected $totalPrice;

    public function __construct(ComponentInterface $componnet,
                                OrderGoodsRepository $orderGoodsRepository,
                                GoodsRepository $goodsRepository)
    {
        parent::__construct($componnet);

        $this->orderGoodsRepository = $orderGoodsRepository;
        $this->goodsRepository = $goodsRepository;

//        if (!$this->goods) {
//            throw new ResourceNotFoundException("不存在此商品", ApiStatus::CODE_4001);
//        }
    }


    public function filter(): bool
    {
        return true;
    }

    public function getData(): array
    {
        return [];
    }

    public function create(): bool
    {
        return true;
    }
}