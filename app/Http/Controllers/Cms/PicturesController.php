<?php

namespace App\Http\Controllers\Cms;

use App\Common\ApiStatus;
use App\Entities\Pictures\PicturesRel;
use App\Entities\Pictures\Repository\UserRepository;
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
            array_push($where, ['name', 'like', '%' . $name . '%']);
        }

        if ($cid = $request->input('cid')) {
            array_push($where, ['category_id', $cid]);
        }


        if ($flag = $request->input('flag')) {
            array_push($where, ['flag', $flag]);
        }

        $result = $this->model->where($where)
            ->withOnly('createdby', ['name', 'email'])
            ->with('cover')
            ->with('category')
            ->whereHas('category', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderBy('id', 'desc')
            ->paginate($pageSize);

        return $this->respond($result);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, UserRepository $userRepository)
    {
        $model = $this->model->with('items')->find($id);

        if ($model) {

            $result = $model->toArray();
            $result['auth'] = 0;
            if ($user = auth('member')->user()) {
                $rel = $userRepository->find($user->id)->pictures;
                $result['auth'] = count($rel) ? 1 : 0;
            }

            $baseUrl = 'http://' . \request()->getHttpHost() . '/storage/';
            $result['base_url'] = $baseUrl;

            return $this->respond($result);
        }
        return $this->failed(ApiStatus::CODE_1021);
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
}
