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

class GoodsDecorator implements CreaterInterface
{
    private $orderComponnet;

    protected $orderGoodsRepository;

    protected $goodsRepository;

    protected $goods_id;

    protected $goods_num;

    protected $goods;

    protected $totalPrice;

    public function __construct(CreaterInterface $componnet, $goods_id, $goods_num=1,
                                OrderGoodsRepository $orderGoodsRepository, GoodsRepository $goodsRepository)
    {
        $this->orderComponnet = $componnet;
        $this->orderGoodsRepository = $orderGoodsRepository;
        $this->goodsRepository = $goodsRepository;

        $this->goods_num = $goods_num;
        $this->goods_id = $goods_id;
        $this->goods = $this->goodsRepository->find($goods_id);

        if (!$this->goods) {
            throw new ResourceNotFoundException("不存在此商品", ApiStatus::CODE_4001);
        }

        $this->setComponents();
    }

    public function setComponents()
    {
        $this->getOrderCreater()->setComponents('goodsDecorator', $this);
    }

    public function getOrderCreater(): CreaterInterface
    {
        return $this->orderComponnet->getOrderCreater();
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
                'num'=>$this->goods_num,
                'model' => $this->goods,
                'total_price' => $this->goods_num * $this->goods->max_price
            ]], $oriData);
    }

    public function create(): bool
    {
        if (!$this->orderComponnet->create()) {
            return false;
        }

        $data = $this->getData();

        $goods = $data['goods']['model'];
        $dbData = [
            'order_id' => $data['order']['order_id'],
            'user_id'  => $data['user']['model']->id,
            'title' => $goods->name,
            'type_id' => $goods->type_id,
            'category_id' => $goods->category_id,
            'goods_no' => $goods->goods_no,
            'sku_no' => '',
            'sku_name' => '',
            'price' => $goods->max_price,
            'origin_price' => $goods->max_price,
            'num' => $data['goods']['num'],
            'note' => '',
        ];

        Log::error(__METHOD__ , $dbData);

        if ($this->orderGoodsRepository->create($dbData)) {
            return true;
        }

        return false;
    }
}