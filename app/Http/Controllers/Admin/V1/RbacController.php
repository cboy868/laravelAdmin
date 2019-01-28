<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/22
 * Time: 14:03
 */

namespace App\Http\Controllers\Admin\V1;


use App\Common\ApiStatus;
use App\Http\Controllers\ApiController;
use App\Repository\Criteria\Status;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Illuminate\Http\Request;
use App\Repository\PostRepository as Post;
use Auth;

class RbacController extends ApiController
{

    /**
     * 工作组权限列表
     */
    public function teams()
    {

    }
    /**
     * 角色管理
     */
    public function roles()
    {

    }

    /**
     * 权限项管理
     */
    public function permissions()
    {

    }

    /**
     * 初始化权限项
     */
    public function initPermissions()
    {

    }

    /**
     * 某角色 的用户列表
     */
    public function roleUser()
    {

    }

    /**
     * 某角色 的权限列表
     */
    public function rolePermissions()
    {

    }

    /**
     * 某用户 的角色列表
     */
    public function userRole()
    {

    }

    /**
     * 为用户添加角色
     */
    public function roleToUser()
    {

    }

    /**
     * 权限项 to Role
     */
    public function PermissionToRole()
    {

    }
}