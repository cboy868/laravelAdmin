<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/18
 * Time: 10:38
 */

namespace App\Entities\Permission\Services;


use Spatie\Permission\Models\Role;

class RolePermissionService
{
    public static function assignRolePermission($role_id, $permission_id)
    {
        $role = Role::findById($role_id, 'admin');

        if (!$role) return false;

        return $role->givePermissionTo($permission_id);
    }

    public static function destoryRolePermission($role_id, $permission_id)
    {
        $role = Role::findById($role_id, 'admin');

        if (!$role) return false;

        return $role->revokePermissionTo($permission_id);
    }

}
