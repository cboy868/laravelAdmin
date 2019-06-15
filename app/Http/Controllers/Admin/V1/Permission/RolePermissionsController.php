<?php

namespace App\Http\Controllers\Admin\V1\Permission;

use App\Common\ApiStatus;
use App\Common\ArrayHelper;
use App\Entities\Permission\Repository\ModelPermissionsRepository;
use App\Entities\Permission\Repository\ModelRolesRepository;
use App\Entities\Permission\Repository\PermissionsRepository;
use App\Entities\Permission\Repository\RolePermissionsRepository;
use App\Entities\Permission\Repository\RolesRepository;
use App\Entities\Permission\Requests\PermissionsRequest;
use App\Entities\Permission\Requests\RolePermissionRequest;
use App\Entities\Permission\Requests\RoleUserRequest;
use App\Entities\Permission\Services\PermissionService;
use App\Entities\Permission\Services\RoleAdminService;
use App\Entities\Permission\Services\RolePermissionService;
use App\Http\Controllers\Admin\AdminController;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Illuminate\Http\Request;

class RolePermissionsController extends AdminController
{
    public $rolePermissionsRepository;

    public function __construct(RolePermissionsRepository $rolePermissionsRepository)
    {
        $this->rolePermissionsRepository = $rolePermissionsRepository;
    }

    /**
     * 初始化所有权限项
     * @return mixed
     */
    public function store(RolePermissionRequest $request)
    {
        $params = $request->only([
            'role_id',
            'permission_id',
        ]);

        try {
            RolePermissionService::assignRolePermission($params['role_id'], $params['permission_id']);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $this->respond();

    }

    /**
     * 角色权限项展示
     * @param $id
     * @param RolesRepository $rolesRepository
     * @return mixed
     */
    public function show($id, RolesRepository $rolesRepository, PermissionsRepository $permissionsRepository)
    {
        $role = $rolesRepository->find($id);

        if (!$role) {
            return $this->respond();
        }

        $pageSize = request()->input('page_size', self::DEFAULT_PAGE_SIZE);

        $rels = $this->rolePermissionsRepository->where(['role_id'=>$role->id])
            ->paginate($pageSize);

        $permission_ids = ArrayHelper::getColumn($rels, 'permission_id');
        $result = $permissionsRepository->whereIn('id', $permission_ids)->paginate();

        if (count($result) == 0) {
            return $this->respond();
        }

        $result = $result->toArray();
        $result['role'] = $role->toArray();

        return $this->respond($result);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function destroy(RoleUserRequest $request)
    {
        $params = $request->only([
            'role_id',
            'permission_id',
        ]);

        try {
            RolePermissionService::destoryRolePermission($params['role_id'], $params['permission_id']);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond();
    }

}
