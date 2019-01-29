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
use App\Repository\Criteria\Status;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Illuminate\Http\Request;
use App\Repository\PostRepository as Post;
use Auth;

class PostController extends AdminController
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
//        $this->post->pushCriteria(new Status($model::STATUS_ACTIVE));

        $pageLevel = $request->input('params.page_size', self::PAGE_SIZE_TWO);
        $pageSize = isset(self::$pageSize[$pageLevel]) ? self::$pageSize[$pageLevel] : 25;

        //查询条件
        $where = [];
        if ($title = $request->input('params.title')) {
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
    public function show()
    {
        $rules = [
            'id' => 'required|integer'
        ];

        if (!$this->_dealParams($rules)) {
            return $this->failed(ApiStatus::CODE_1001, session()->get(self::SESSION_ERR_KEY));
        }

        $model = $this->post->find($this->params['id']);

        if ($model) {
            return $this->respond($model->toArray());
        }

        return $this->failed(ApiStatus::CODE_1021);
    }


    /**
     * 创建新model
     */
    public function create()
    {
        $rules = [
            'title' => 'required',
            'content' => 'required'
        ];

        if (!$this->_dealParams($rules)) {
            return $this->failed(ApiStatus::CODE_1001, session()->get(self::SESSION_ERR_KEY));
        }

        try {
            $params = $this->params;
            $params['posted_at'] = $params['posted_at'] ?? date("Y-m-d H:i:s");
            $params['author_id'] = $this->user->id;

            $model = $this->post->create($params);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond($model->toArray());
    }

    /**
     * 修改
     */
    public function edit()
    {
        $rules = [
            'id' => 'required|integer',
        ];

        if (!$this->_dealParams($rules)) {
            return $this->failed(ApiStatus::CODE_1001, session()->get(self::SESSION_ERR_KEY));
        }

        try {
            $params = $this->params;
            $model = $this->post->update($params, $params['id']);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond($model->toArray());
    }

    /**
     * 软删除
     * @return mixed
     * @throws \Exception
     */
    public function delete()
    {
        $rules = [
            'id' => 'required|integer',
        ];

        if (!$this->_dealParams($rules)) {
            return $this->failed(ApiStatus::CODE_1001, session()->get(self::SESSION_ERR_KEY));
        }

        try {
            $result = $this->post->trash($this->params['id']);
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
    public function restore()
    {
        $rules = [
            'id' => 'required|integer',
        ];

        if (!$this->_dealParams($rules)) {
            return $this->failed(ApiStatus::CODE_1001, session()->get(self::SESSION_ERR_KEY));
        }

        try {
            $result = $this->post->restore($this->params['id']);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond($result);
    }
}