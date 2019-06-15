<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/18
 * Time: 10:38
 */

namespace App\Entities\Permission\Services;


use App\Common\ArrayHelper;
use Illuminate\Support\Facades\DB;

class PermissionService
{

    static $ignore = ['login'];

    /**
     * 刷权限表，获取所有权限项,加入管理
     */
    public static function syncPermissions()
    {
        //所有权限项
        $permissions = self::allPermissions();

        //已经在数据库中的permissions
        $dbPermissions = DB::table(config('permission.table_names.permissions'))->get();

        $dbPermissionsKey =  array_keys(ArrayHelper::index($dbPermissions, 'name'));

        $diffPermissions = array_filter($permissions, function ($item) use ($dbPermissionsKey){
            return in_array($item['name'], $dbPermissionsKey) ? false : true;
        });

        DB::table('auth_permissions')->insert($diffPermissions);
    }


    /**
     * 获取所有权限项
     * @return array
     */
    public static function allPermissions()
    {
        $app = app();
        $routes = $app->routes->getRoutes();

        $permissions = [];
        $time = date('Y-m-d H:i:s');

        foreach ($routes as $k=>$value){
            if (strpos($value->uri, 'api/admin') === false) continue;
            $name = $value->getName();
            if (!$name) continue; //没有name则跳过
            if (in_array($name, self::$ignore)) continue;//排除不加入管理的项

            array_push($permissions, [
                'title' => $name,
                'name' => $name,
                'guard_name' => 'api',
                'created_at' => $time,
                'updated_at' => $time
            ]);
        }

        return $permissions;
    }

}
