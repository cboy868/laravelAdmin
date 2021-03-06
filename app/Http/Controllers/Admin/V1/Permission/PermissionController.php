<?php

namespace App\Http\Controllers\Admin\V1\Permission;

use App\Common\ApiStatus;
use App\Entities\Permission\Repository\PermissionsRepository;
use App\Entities\Permission\Requests\PermissionsRequest;
use App\Entities\Permission\Services\PermissionService;
use App\Http\Controllers\Admin\AdminController;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Illuminate\Http\Request;

class PermissionController extends AdminController
{
    public $permissionsRepository;

    public function __construct(PermissionsRepository $permissionsRepository)
    {
        $this->permissionsRepository = $permissionsRepository;
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

        if ($name = $request->input('name')) {
            array_push($where, ['name', 'like', '%'.$name.'%']);
        }

        $result = $this->permissionsRepository->where($where)
            ->orderBy('id', 'ASC')
            ->paginate($pageSize);

        return $this->respond($result);
    }

    /**
     * 初始化所有权限项
     * @return mixed
     */
    public function store()
    {
        if (!PermissionService::syncPermissions()){
            return $this->failed(ApiStatus::CODE_1011, '权限初始化失败');
        }
        return $this->respond();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionsRequest $request, $id)
    {
        $params = $request->only([
            'name',
            'title',
            'guard_name'
        ]);
        try {
            $this->permissionsRepository->update($params, $id);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond([]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $result = $this->permissionsRepository->trash($id);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond($result);
    }

}
