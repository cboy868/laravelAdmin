<?php

namespace App\Http\Controllers\Admin\V1\Cms;

use App\Common\ApiStatus;
use App\Entities\Focus\Repository\FocusItemRepository;
use App\Entities\Focus\Repository\FocusRepository;
use App\Entities\Focus\Requests\FocusRequest;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Cboy868\Repositories\Exceptions\RepositoryException;

class FocusController extends ApiController
{
    public $focusRepository;

    public $focusItemRepository;

    public function __construct(FocusRepository $focusRepository, FocusItemRepository $focusItemRepository)
    {
        $this->focusRepository = $focusRepository;

        $this->focusItemRepository = $focusItemRepository;

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

        if ($pos = $request->input('pos')) {
            array_push($where, ['pos', $pos]);
        }

        if ($appid = $request->input('appid')) {
            array_push($where, ['appid', $appid]);
        }

        $result = $this->focusRepository->where($where)
            ->with('items')
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
    public function store(FocusRequest $request)
    {
        $params = array_filters($request->input());
        try {
            $model = $this->focusRepository->create($params);
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
    public function show($id)
    {
        $model = $this->focusRepository->with('items')->find($id);

        if ($model) {

            $result = $model->toArray();
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
    public function update(Request $request, $id)
    {
        $params = array_filters($request->input());
        try {
            unset($params['_method']);
            $this->focusRepository->update($params, $id);
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
            $result = $this->focusRepository->trash($id);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond($result);
    }


    /**
     * 图片上传
     */
    public function upload(Request $request)
    {

        $id = $request->input('id');

        if (!$id) {
            return $this->failed(ApiStatus::CODE_1001);
        }

        $path = $request->file('focus')->store(
            'focus', 'public'
        );

        $model = $this->focusItemRepository->create([
            'fid' => $id,
            'path' => $path,
            'link' => '',
            'title' => '',
            'intro' => '',
        ]);

        return $this->respond([
            'model' => $model->toArray(),
            'path' => 'http://' . \request()->getHttpHost() . '/storage/' . $path,
            'params' => $request->input()
        ]);
    }

    /**
     * 更新图片信息
     * @param Request $request
     * @return mixed
     * @throws RepositoryException
     */
    public function updateItem($id, Request $request)
    {
        $params = array_filters($request->input());
        try {
            $this->focusItemRepository->update($params, $id);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond([]);
    }

    /**
     * 删除图片信息
     * @param Request $request
     * @return mixed
     * @throws RepositoryException
     */
    public function deleteItem($id)
    {
        try {
            $result = $this->focusItemRepository->trash($id);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond($result);
    }
}
