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

class AdminController extends ApiController
{
}