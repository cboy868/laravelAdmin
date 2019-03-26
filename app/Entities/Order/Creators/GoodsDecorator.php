<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 22:14
 */

namespace App\Entities\Order\Creators;

use App\Entities\Goods\Repository\GoodsRepository;

class GoodsDecorator implements CreaterInterface
{
    private $orderComponnet;

    protected $goodsRepository;

    protected $goods_id;

    protected $goods_num;

    protected $goods;

    public function __construct(CreaterInterface $componnet, $goods_id, $goods_num=1, GoodsRepository $goodsRepository)
    {
        $this->orderComponnet = $componnet;
        $this->goodsRepository = $goodsRepository;

        $this->goods_num = $goods_num;
        $this->goods_id = $goods_id;
        $this->goods = $this->goodsRepository->find($goods_id);
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
        $oriData = $this->orderComponnet->getData();

        return array_merge([
            'goods'=>[
                'id'=>$this->goods->id,
                'num'=>$this->goods_num
            ]], $oriData);
    }

    public function create(): bool
    {
        // TODO: Implement create() method.
    }
}