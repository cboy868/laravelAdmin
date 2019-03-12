<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/22
 * Time: 14:03
 */

namespace App\Http\Controllers\Cms;


use App\Common\ApiStatus;
use App\Http\Controllers\ApiController;
use App\Http\Resources\PostCollection;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Illuminate\Http\Request;
use App\Repository\PostRepository as Post;
use Auth;

class PostController extends ApiController
{
    protected $post;

    public function __construct(Post $post)
    {
        parent::__construct();

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
//        $this->post->pushCriteria(new Status($model::STATUS_ACTIVE));

        $pageLevel = $request->input('page_size', self::PAGE_SIZE_TWO);
        $pageSize = isset(self::$pageSize[$pageLevel]) ? self::$pageSize[$pageLevel] : 25;

        //查询条件
        $where = [];
        if ($title = $request->input('title')) {
            array_push($where, ['title', 'like', '%'.$title.'%']);
        }

        $result = $this->post->where($where)
                 ->orderBy('id', 'DESC')
                 ->with('author')
                 ->paginate($pageSize);

        return $this->respond($result);
    }


    /**
     * Return the specified resource.
     */
    public function show($id)
    {
        $model = $this->post->find($id);

        if ($model) {
            return $this->respond($model->toArray());
        }
        return $this->failed(ApiStatus::CODE_1021);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = $this->post->withTrashed()->find($id);

        if ($model) {
            return $this->respond($model->toArray());
        }
        return $this->failed(ApiStatus::CODE_1021);
    }


    /**
     * 创建新model
     */
    public function store(\App\Http\Requests\StorePostRequest $postRequest)
    {
        $params = $postRequest->input();

        try {
            $params['posted_at'] = $params['posted_at'] ?? date("Y-m-d H:i:s");
//            $params['author_id'] = $this->user->id;
            $params['author_id'] = auth('admin')->user()->id;

            $model = $this->post->create($params);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $this->respond($model->toArray());
    }

    /**
     * 修改
     */
    public function update(\App\Http\Requests\UpdatePostRequest $postRequest, $id)
    {

        $params = $postRequest->input();
        try {
            if (isset($params['type']) && $params['type'] == 'restore') { //数据恢复
                $this->post->restore($id);
            } else {
                $this->post->update($params, $id);
            }
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond([]);
    }

    /**
     * 软删除
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $result = $this->post->trash($id);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond($result);
    }

    /**
     * 恢复软删除数据
     * @return mixed
     * @throws \Exception
     */
//    public function restore(\App\Http\Requests\UpdatePostRequest $postRequest)
//    {
//        try {
//            $result = $this->post->restore($postRequest->input('params.id'));
//        } catch (RepositoryException $e) {
//            throw new \Exception($e->getMessage(), $e->getCode());
//        }
//        return $this->respond($result);
//    }
}