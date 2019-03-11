<?php

namespace App\Http\Controllers\Goods;

use App\Entities\Goods\Repository\BrandRepository;
use App\Entities\Goods\Requests\BrandRequest;
use App\Http\Controllers\ApiController;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Illuminate\Http\Request;

class BrandController extends ApiController
{

    public $brand;

    public function __construct(BrandRepository $model)
    {
        $this->brand = $model;

        parent::__construct();
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
        if ($name = $request->input('name')) {
            array_push($where, ['name', 'like', '%'.$name.'%']);
        }

        $result = $this->brand->where($where)
            ->orderBy('id', 'desc')
            ->paginate($pageSize);

        return $this->respond($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandRequest $request)
    {
        $params = array_filters($request->input());
        try {
            $model = $this->brand->create($params);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $this->respond($model->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BrandRequest $request, $id)
    {
        $params = array_filters($request->input());

        try {
            unset($params['_method']);

            $this->brand->withTrashed()->update($params, $id);

        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond([]);
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
