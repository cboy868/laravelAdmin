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
use Illuminate\Http\Request;
use Response;
use App\Repository\PostRepository as Post;

class PostController extends ApiController
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * 文章列表
     * @return mixed
     * 可传参数 params中 title,pageSize
     */
    public function index(Request $request)
    {

//        $model = $this->post->model();
//
//        $this->post->pushCriteria(new Status($model::STATUS_ACTIVE));




        return $this->respond($this->post->where([
            ['title', 'like', 'the-%']
        ])->orderBy('id', 'DESC')->paginate(25, ['title']));


//        $pageLevel = $request->input('params.page_size', self::PAGE_SIZE_TWO);
//        $pageSize = isset(self::$pageSize[$pageLevel]) ? self::$pageSize[$pageLevel] : 20;
//
//        $result = PostResource::collection(
//            Post::search($request->input("params.title"))
//                ->withCount('likes')
//                ->with('author')
//                ->latest()
//                ->paginate($pageSize)
//        );
//
//        return $this->respond($result);
    }

    /**
     * Return the specified resource.
     */
    public function show(Request $request)
    {
        $rules = [
            'id' => 'required|integer'
        ];

        if (!$this->_dealParams($rules)) {
            return $this->failed(ApiStatus::CODE_1001, session()->get(self::SESSION_ERR_KEY));
        }

        $model = Post::find($this->params['id']);

        if ($model) {
            return $this->respond($model->toArray());
        }

        return $this->failed(ApiStatus::CODE_1021);
    }
}