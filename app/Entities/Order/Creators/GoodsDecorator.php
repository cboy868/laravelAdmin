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
use App\Entities\Order\Repository\OrderGoodsRepository;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class GoodsDecorator extends DecoratorAbs
{
    protected $orderGoodsRepository;

    protected $goodsRepository;

    protected $goods_id;

    protected $goods_num;

    protected $goods;

    protected $totalPrice;

    public function __construct(ComponentInterface $componnet, $goods_id, $goods_num=1,
                                OrderGoodsRepository $orderGoodsRepository, GoodsRepository $goodsRepository)
    {
        parent::__construct($componnet);

        $this->orderGoodsRepository = $orderGoodsRepository;
        $this->goodsRepository = $goodsRepository;

        $this->goods_num = $goods_num;
        $this->goods_id = $goods_id;
        $this->goods = $this->goodsRepository->find($goods_id);

        if (!$this->goods) {
            throw new ResourceNotFoundException("不存在此商品", ApiStatus::CODE_4001);
        }
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