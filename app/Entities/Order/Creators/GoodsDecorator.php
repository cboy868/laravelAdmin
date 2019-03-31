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
use Cboy868\Repositories\Exceptions\RepositoryException;

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
        return $this->component->getData();
    }

    public function create(): bool
    {

        if (!$this->component->create()) {
            return false;
        }

        $data = $this->getData();
        $goodsData = $data['goods'];

        foreach ($goodsData as $goods) {
            $dbData = [
                'order_id' => $data['order']['order_id'],
                'user_id'  => $data['user']['id'],
                'title' => $goods['name'],
                'type_id' => $goods['type_id'] ?? 0,
                'category_id' => $goods['category_id'],
                'goods_no' => $goods['goods_no'],
                'sku_no' => '',
                'sku_name' => '',
                'price' => $goods['price'],
                'origin_price' => $goods['origin_price'],
                'num' => $goods['num'],
                'note' => '',
            ];

            if (!$this->orderGoodsRepository->create($dbData)) {
                throw new \Exception('数据添加失败', ApiStatus::CODE_4002);
            }
        }

        return true;
    }
}