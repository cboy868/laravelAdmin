<?php

namespace App\Http\Controllers\Admin\V1\Permission;

use App\Common\ApiStatus;
use App\Entities\Permission\Repository\RolesRepository;
use App\Entities\Permission\Requests\RoleRequest;
use App\Entities\Permission\Services\PermissionService;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ApiController;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends ApiController
{
    public $rolesRepository;

    public function __construct(RolesRepository $rolesRepository)
    {
        $this->rolesRepository = $rolesRepository;
    }


    /**
     * 列表
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $pageSize = $request->input('page_size', self::DEFAULT_PAGE_SIZE);

        //查询条件
        $where = [];
        if ($title = $request->input('title')) {
            array_push($where, ['title', 'like', '%'.$title.'%']);
        }

        $result = $this->rolesRepository->where($where)
            ->orderBy('id', 'ASC')
            ->paginate($pageSize);

        return $this->respond($result);
    }


    /**
     * 保存
     */
    public function store(RoleRequest $request)
    {
        $params = $request->only([
            'name',
            'title',
        ]);
        $params['guard_name'] = 'admin';
        $params['updated_at'] = $params['created_at'] = time();

        try {
            $model = $this->rolesRepository->create($params);
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
    public function update(RoleRequest $request, $id)
    {
        $params = $request->only([
            'name',
            'title',
            'guard_name'
        ]);
        try {
            $this->rolesRepository->update($params, $id);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond([]);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function show($id) {
        $model = $this->rolesRepository->find($id);

        if ($model) {
            return $this->respond($model->toArray());
        }

        return $this->failed(ApiStatus::CODE_1021);
    }


    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $result = $this->rolesRepository->trash($id);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond($result);
    }
}
