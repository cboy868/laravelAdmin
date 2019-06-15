<?php

namespace App\Http\Controllers\Admin\V1\Permission;

use App\Common\ApiStatus;
use App\Common\ArrayHelper;
use App\Entities\Permission\Repository\ModelRolesRepository;
use App\Entities\Permission\Repository\PermissionsRepository;
use App\Entities\Permission\Repository\RolesRepository;
use App\Entities\Permission\Repository\UsersRepository;
use App\Entities\Permission\Requests\PermissionsRequest;
use App\Entities\Permission\Requests\RoleUserRequest;
use App\Entities\Permission\Services\PermissionService;
use App\Entities\Permission\Services\RoleAdminService;
use App\Http\Controllers\Admin\AdminController;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Illuminate\Http\Request;

class RoleUsersController extends AdminController
{
    public $modelRolesRepository;

    public function __construct(ModelRolesRepository $modelRolesRepository)
    {
        $this->modelRolesRepository = $modelRolesRepository;
    }

    /**
     * 初始化所有权限项
     * @return mixed
     */
    public function store(RoleUserRequest $request)
    {
        $params = $request->only([
            'role_name',
            'user_id',
        ]);

        try {
            RoleAdminService::assignRoleUser($params['role_name'], $params['user_id']);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $this->respond();

    }

    /**
     * 角色用户展示
     * @param $id
     * @param RolesRepository $rolesRepository
     * @return mixed
     */
    public function show($id, RolesRepository $rolesRepository, UsersRepository $usersRepository)
    {
        $role = $rolesRepository->find($id);

        if (!$role) {
            return $this->respond();
        }

        $pageSize = request()->input('page_size', self::DEFAULT_PAGE_SIZE);

        $rels = $this->modelRolesRepository->where(['role_id'=>$role->id])
            ->paginate($pageSize);


        $adminIds = ArrayHelper::getColumn($rels, 'model_id');
        $result = $usersRepository->whereIn('id', $adminIds)->paginate();

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
            'role_name',
            'user_id',
        ]);

        try {
            RoleAdminService::destoryRoleUser($params['role_name'], $params['user_id']);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond();
    }

}
