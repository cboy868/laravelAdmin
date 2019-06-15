<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/18
 * Time: 10:38
 */

namespace App\Entities\Permission\Services;


use App\Admin;

class RoleAdminService
{
    public static function assignRoleUser($roleName, $user_id)
    {
        $admin = Admin::find($user_id);

        if (!$admin) return false;

        return $admin->assignRole($roleName);
    }

    public static function destoryRoleUser($roleName, $user_id)
    {
        $admin = Admin::find($user_id);

        if (!$admin) return false;

        return $admin->removeRole($roleName);
    }

}
