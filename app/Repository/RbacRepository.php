<?php

namespace App\Repository;

use App\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:28
 */

class RbacRepository
{
    /**
     * 创建角色
     * @param array $attributes
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function createRole(array $attributes=[])
    {
       return Role::create($attributes);
    }

    /**
     * 获取所有角色
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function roles(array $where=[])
    {
        return Role::where($where)->get();
    }

    /**
     * 根据角色名查找角色
     * @param string $name
     * @param null $guardName
     * @return \Spatie\Permission\Contracts\Role|Role
     */
    public function findByRoleName(string $name, $guardName = null)
    {
        return Role::findByName($name, $guardName);
    }

    /**
     * 创建权限项
     * @param array $attributes
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function createPermission(array $attributes=[])
    {
        return Permission::create($attributes);
    }

    /**
     * 获取所有权限项
     * @param array $where
     * @return mixed
     */
    public function permissions(array $where = [])
    {
        return Permission::where($where)->get();
    }


    /**
     * 根据名字获取权限项
     * @param string $name
     * @param null $guardName
     * @return \Spatie\Permission\Contracts\Permission
     */
    public function findByPermissionName(string $name, $guardName = null)
    {
        return Permission::findByName($name, $guardName);
    }


    /**
     * todo 待完善
     * 获取角色下用户
     * @param string $name
     * @param string|null $guard_name
     */
    public function roleUsers(string $name, string $guard_name=null)
    {
        $role = Role::findByName($name, $guard_name);
    }

    /**
     * todo 待完善
     * 获取角色下权限项
     * @param string $name
     * @param string|null $guard_name
     */
    public function rolePermissions(string $name, string $guard_name=null)
    {
        $role = Role::findByName($name, $guard_name);
    }



    public function userRoles()
    {
        
    }
}