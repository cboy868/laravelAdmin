<?php

namespace App\Http\Controllers\Order;

use App\Common\ApiStatus;
use App\Entities\Order\Repository\OrderRepository;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends ApiController
{

    protected $order;

    public function __construct(OrderRepository $orderRepository)
    {
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
