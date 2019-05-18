<?php

namespace App\Http\Controllers\Admin\V1\Cms;

use App\Common\ApiStatus;
use App\Entities\Pictures\Repository\CartoonRepository;
use App\Entities\Pictures\Repository\CategoryRepository;
use App\Entities\Pictures\Repository\PicturesUserRelRepository;
use App\Entities\Pictures\Repository\UserRepository;
use App\Entities\Pictures\Requests\StorePicturesRequest;
use App\Entities\Pictures\Requests\UpdatePicturesRequest;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use App\Entities\Pictures\Repository\PicturesRepository;
use Cboy868\Repositories\Exceptions\RepositoryException;

class PicturesController extends AdminController
{
    public $model;

    protected $categoryRepository;

    public function __construct(PicturesRepository $model, CategoryRepository $categoryRepository)
    {
        $this->model = $model;
        $this->categoryRepository = $categoryRepository;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $pageSize = $request->input('page_size', 20);

        //查询条件
        $where = [];
        if ($name = $request->input('name')) {
            array_push($where, ['name', 'like', '%' . $name . '%']);
        }

        if ($cid = $request->input('cid')) {
            array_push($where, ['category_id', $cid]);
        }


        if ($flag = $request->input('flag')) {
            array_push($where, ['flag', $flag]);
        }

        $type = $request->input('type', 1);

        array_push($where, ['type' => $type]);

        $result = $this->model->where($where)
            ->withOnly('createdby', ['name', 'email'])
            ->with('cover')
            ->with('category')
            ->whereHas('category', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderBy('id', 'desc')
            ->paginate($pageSize);


        if (!$result) {
            return $this->failed(ApiStatus::CODE_1021);
        }

        $categorys = $this->categoryRepository->all();

        $res = $result->toArray();
        $baseUrl = 'http://' . \request()->getHttpHost() . '/storage/';
        $res['base_url'] = $baseUrl;
        $res['categorys'] = $categorys;

        return $this->respond($res);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePicturesRequest $request)
    {
        $params = $request->only(['id', 'type', 'category_id', 'intro', 'name', 'author', 'flag']);

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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, CartoonRepository $cartoonRepository)
    {

        $model = $this->model->find($id);

        if (!$model) {
            return $this->respond();
        }

        $chapters = $cartoonRepository->where(['pictures_id'=>$id])
            ->orderBy('chapter', 'ASC')
            ->paginate(20);

        $result = [];
        if ($chapters) {
            $result = $chapters->toArray();
        }
        $result['base_url'] = 'http://' . \request()->getHttpHost() . '/storage/';
        $result['picture'] = $model->toArray();

        return $this->respond($result);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePicturesRequest $request, $id)
    {
        $params = $request->only(['id', 'category_id', 'intro', 'name', 'author', 'flag']);

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
     * @param  int $id
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


    /**
     * 修改封面
     * @param Request $request
     * @return mixed
     * @throws RepositoryException
     */
    public function cover(Request $request)
    {

        $id = $request->input('id');

        if (!$id) {
            return $this->failed(ApiStatus::CODE_1001);
        }

        $path = $request->file('cover')->store(
            'covers', 'public'
        );

        $this->model->update(['thumb'=>$path], $id);

        return $this->respond([
            'path' =>  'http://' . \request()->getHttpHost() . '/storage/' . $path,
            'params' => $request->input()
        ]);

    }
}