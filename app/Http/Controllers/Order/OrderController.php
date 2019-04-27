<?php

namespace App\Http\Controllers\Order;

use App\Common\ApiStatus;
use App\Entities\Order\Repository\OrderRepository;
use App\Entities\Order\Requests\OrderCreateRequest;
use App\Entities\Order\Services\OrderService;
use App\Entities\Order\Services\PicturesOrderService;
use App\Entities\Pictures\User;
use App\Entities\Wechat\Services\Order;
use App\Events\UserLogin;
use App\Http\Controllers\ApiController;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\UnauthorizedException;

class OrderController extends ApiController
{

    protected $picturesOrderService;

    protected $order;

    public function __construct(PicturesOrderService $picturesOrderService, OrderRepository $orderRepository)
    {
        $this->picturesOrderService = $picturesOrderService;
        $this->order = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->input('page_size', self::DEFAULT_PAGE_SIZE);

        //查询条件
        $where = [];
        if ($order_no = $request->input('order_no')) {
            array_push($where, ['order_no', $order_no]);
        }
        $result = $this->order->where($where)
            ->with("goods")
            ->orderBy('id', 'desc')
            ->paginate($pageSize);

        if (!$result) {
            return $this->failed(ApiStatus::CODE_1021);
        }
        $res = $result->toArray();
        return $this->respond($res);
    }


    /**
     *
     *  $params = [
            'goods_no' => 1,
            'num' => 1
        ];

        $params = [
            [
            'goods_no' => 'a111',
            'num' => 2
            ],
            [
            'goods_no' => 'a222',
            'num' => 2
            ]
        ];
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderCreateRequest $request)
    {
        $params = array_filters($request->input());

        try {

            $result = $this->picturesOrderService->create($params);

            if (!$result) {
                throw new \Exception("下单失败");
            }



        } catch (UnauthorizedException $e) {
            return $this->failed(ApiStatus::CODE_2002);
        } catch (\Exception $e) {
            return $this->failed(ApiStatus::CODE_4003);
        }

        return $this->respond($orderResult);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = $this->order->with('goods')->find($id);

        if ($model) {
            $result = $model->toArray();
            return $this->respond($result);
        }

        return $this->failed(ApiStatus::CODE_1021);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
