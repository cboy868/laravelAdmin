<?php

namespace App\Http\Controllers\Admin\V1\Permission;

use App\Entities\Permission\Services\PermissionService;
use App\Http\Controllers\Admin\AdminController;

class PermissionController extends AdminController
{
    public function permissionSync()
    {
        PermissionService::syncPermissions();
    }
}
