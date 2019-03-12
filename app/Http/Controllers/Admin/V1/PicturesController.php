<?php

namespace App\Http\Controllers\Admin\V1;

use App\Common\ApiStatus;
use App\Entities\Pictures\Requests\StorePicturesRequest;
use App\Entities\Pictures\Requests\UpdatePicturesRequest;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Entities\Pictures\Repository\PicturesRepository;
use Cboy868\Repositories\Exceptions\RepositoryException;

class PicturesController extends ApiController
{

    public $model;

    public function __construct(PicturesRepository $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageLevel = $request->input('page_size', self::PAGE_SIZE_TWO);
        $pageSize = isset(self::$pageSize[$pageLevel]) ? self::$pageSize[$pageLevel] : 25;

        //查询条件
        $where = [];
        if ($name = $request->input('name')) {
            array_push($where, ['name', 'like', '%'.$name.'%']);
        }

        if ($cid = $request->input('cid')) {
            array_push($where, ['category_id', $cid]);
        }

        $result = $this->model->where($where)
            ->with(['category'])
            ->withOnly('createdby', ['name', 'email'])
            ->orderBy('sort', 'DESC')
            ->paginate($pageSize);

        return $this->respond($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePicturesRequest $request)
    {
        $params = array_filters($request->input());
        try {
            $params['created_by'] = auth('admin')->user()->id;
            $model = $this->model->create($params);
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
        $model = $this->model->with('items')->find($id);

        if ($model) {
            return $this->respond($model->items->toArray());
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
    public function update(UpdatePicturesRequest $request, $id)
    {
        $params = array_filters($request->input());
        try {
            unset($params['_method']);
            $this->model->update($params, $id);
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
        try {
            $result = $this->model->trash($id);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond($result);
    }
}
