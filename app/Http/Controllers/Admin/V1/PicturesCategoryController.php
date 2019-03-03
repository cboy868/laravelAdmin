<?php

namespace App\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use App\Entities\Pictures\Requests\StoreCategoryRequest;
use App\Entities\Pictures\Requests\UpdateCategoryRequest;
use Cboy868\Repositories\Exceptions\RepositoryException;
use App\Entities\Pictures\Repository\CategoryRepository;

class PicturesCategoryController extends AdminController
{
    public $model;

    public function __construct(CategoryRepository $model)
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

        $result = $this->model->where($where)
            ->orderBy('id', 'DESC')
            ->paginate($pageSize);

        return $this->respond($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $params = array_filter($request->input());

        try {
            $model = $this->model->create($params);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $this->respond($model->toArray());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $params = array_filters($request->input(), ['null', '']);

        try {
            unset($params['_method']);
            $this->model->withTrashed()->update($params, $id);
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
