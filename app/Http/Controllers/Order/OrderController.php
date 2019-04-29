<?php

namespace App\Http\Controllers\Order;

use App\Common\ApiStatus;
use App\Entities\Order\Repository\OrderRepository;
use App\Entities\Order\Requests\OrderCreateRequest;
use App\Entities\Order\Services\PicturesOrderService;
use App\Entities\Pictures\Repository\PicturesRepository;
use App\Entities\Wechat\Services\Order;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
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
    public function index(Request $request, PicturesRepository $picturesRepository)
    {
        $pageSize = $request->input('page_size', self::DEFAULT_PAGE_SIZE);

        $user = auth('member')->user();

        if (!$user) {
            return $this->failed(ApiStatus::CODE_2002);
        }

        //查询条件
        $where = [];
        if ($order_no = $request->input('order_no')) {
            array_push($where, ['order_no', $order_no]);
        }

        $where['user_id'] = $user->id;

        $result = $this->order->where($where)
            ->with("goods")
            ->orderBy('id', 'desc')
            ->paginate($pageSize);

        if (!$result) {
            return $this->respond([]);
        }

        $res = $result->toArray();

        $pictureIds = [];
        foreach ($res['data'] as $v) {
            array_push($pictureIds, $v['goods'][0]['goods_no']);
        }

        $data = $picturesRepository->whereIn('id', $pictureIds)
            ->with('cover')
            ->get();


        $data = $data->toArray();

        $new_data = [];
        foreach ($data as $d) {
            $new_data[$d['id']] = $d;
        }


        foreach ($res['data'] as &$it) {
            $it['goods'][0]['cover'] = $new_data[$it['goods'][0]['goods_no']]['cover'];
        }unset($it);


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

        return $this->respond($result['order']);
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
