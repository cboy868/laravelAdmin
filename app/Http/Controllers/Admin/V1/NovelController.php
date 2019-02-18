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
use App\Http\Resources\PostCollection;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Illuminate\Http\Request;
use Auth;
use App\Entities\Novel\Repository\NovelRepository;

class NovelController extends AdminController
{
    protected $model;

    public function __construct(NovelRepository $model)
    {
        $this->model = $model;
    }

    /**
     * 文章列表
     * @return mixed
     * 可传参数 params中 title,pageSize
     */
    public function index(Request $request)
    {

        $pageLevel = $request->input('params.page_size', self::PAGE_SIZE_TWO);
        $pageSize = isset(self::$pageSize[$pageLevel]) ? self::$pageSize[$pageLevel] : 25;

        //查询条件
        $where = [];
        if ($title = $request->input('params.title')) {
            array_push($where, ['title', 'like', '%'.$title.'%']);
        }

        $result = $this->model->where($where)
                 ->orderBy('id', 'DESC')
                 ->with('author')
                 ->paginate($pageSize);

        return $this->respond($result);
    }

    /**
     * Return the specified resource.
     */
    public function show(\App\Http\Requests\PostRequest $postRequest)
    {
        $model = $this->post->find($postRequest->input('params.id'));

        if ($model) {
            return $this->respond($model->toArray());
        }

        return $this->failed(ApiStatus::CODE_1021);
    }


    /**
     * 创建新model
     */
    public function create(\App\Http\Requests\PostRequest $postRequest)
    {
        $params = $postRequest->input('params');

        try {
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
    public function edit(\App\Http\Requests\PostRequest $postRequest)
    {
        $params = $postRequest->input('params');

        try {
            $this->post->update($params, $params['id']);
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
    public function delete(\App\Http\Requests\PostRequest $postRequest)
    {
        try {
            $result = $this->post->trash($postRequest->input('params.id'));
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
    public function restore(\App\Http\Requests\PostRequest $postRequest)
    {
        try {
            $result = $this->post->restore($postRequest->input('params.id'));
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond($result);
    }
}