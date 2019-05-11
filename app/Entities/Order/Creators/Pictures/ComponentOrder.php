<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 22:14
 */

namespace App\Entities\Order\Creators\Pictures;

use App\Common\ApiStatus;
use App\Entities\Goods\Repository\GoodsRepository;
use App\Entities\Order\Creators\ComponentInterface;
use App\Entities\Order\Helper;
use App\Entities\Order\Repository\OrderRepository;
use App\Entities\Pictures\Repository\PicturesRepository;
use App\IUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ComponentOrder implements ComponentInterface
{

    protected $orderNo;

    protected $orderId;

    protected $user;

    //传进来的商品参数
    protected $goodsParams;

    //根据商品参数查出的商品数据
    protected $goods;

    //订单商品要用到的数据
    protected $orderGoods;

    protected $picturesRepository;

    protected $orderRepository;

    protected $components = [];

    protected $totalPrice = 0;

    protected $title = '';


    public function __construct(OrderRepository $orderRepository,
                                PicturesRepository $picturesRepository,
                                IUser $user, Array $goodsParams)
    {
        $this->orderRepository = $orderRepository;
        $this->picturesRepository = $picturesRepository;
        $this->orderNo = Helper::createOrderNo();

        $this->user = $user;
        $this->goodsParams = $goodsParams;

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
                'price' => $this->totalPrice,
                'title' => $this->title
            ],
            'user' => $this->user->toArray(),
            'goods' => $this->orderGoods
        ];
    }

    public function create(): bool
    {
        $dbData = [
            'order_no' => $this->orderNo,
            'user_id'  => $this->user->id,
            'title' => $this->title,
            'price' => $this->totalPrice,
            'origin_price' => $this->totalPrice,
            'type' => 1,
        ];

        if ($model = $this->orderRepository->create($dbData)) {
            $this->orderId = $model->id;
            return true;
        }

        return false;
    }

    public function parseGoodsInfo()
    {
        if (!is_array($this->goodsParams)) {
            throw new \Exception("数据格式错误" . ApiStatus::CODE_1001);
        }

        $goodsNos = array_column($this->goodsParams, 'goods_no');

        $goodsModels = $this->picturesRepository->whereIn('id', $goodsNos)->get();

        if (!$goodsModels) {
            throw new ResourceNotFoundException("对应商品不存在", ApiStatus::CODE_1021);
        }

        $this->goods = $goodsModels;

        Log::error($this->goods);

        $kGoodsParams = collect($this->goodsParams)->keyBy('id');

        Log::error(__METHOD__, [
            'a' => $kGoodsParams
        ]);

        $data = [];
        foreach ($this->goods as $item) {
//            if (!isset($kGoodsParams[$item->goods_no]['num']) || !$kGoodsParams[$item->goods_no]['num']){
//                continue;
//            }

            $data[$item['id']] = [
                'goods_id' => $item->id,
                'type_id' => 0,
                'goods_no' => $item->id,
                'sku_no' => '',
                'sku_name' => '',
                'price' => $item->getPrice(),
                'origin_price' => $item->getPrice(),
                'num' => $kGoodsParams[$item->goods_no]['num'] ?? 1,
                'note' => '',
                'name' => $item->name
            ];

            $num = 1;
            if (isset($kGoodsParams[$item->goods_no]['num']) && $kGoodsParams[$item->goods_no]['num']) {
                $num = $kGoodsParams[$item->goods_no]['num'];
            }

            $this->totalPrice += $item->getPrice() * $num;

            $this->title .= $item->name . ',';
        }

        if (!$data) {
            throw new \Exception("请选择正确的商品下单", ApiStatus::CODE_1021);
        }

        $this->orderGoods = $data;

    }
}