<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/22
 * Time: 14:03
 */

namespace App\Http\Controllers\Mobile\V1;


use App\Common\ApiStatus;
use Illuminate\Http\Request;
use App\Entities\Novel\Repository\NovelRepository;
use App\Entities\Novel\Requests\NovelRequest;

class NovelController extends MobileController
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
     * 单页信息,用户请求
     * @param NovelRequest $novelRequest
     * @return mixed
     */
    public function show(NovelRequest $novelRequest)
    {
        $model = $this->model->with('author')
            ->with(['contents'=>function($query) use ($novelRequest){
                $cpage = $novelRequest->input('params.page', 1);
                $chapter = $novelRequest->input('params.chapter', 1);

                $query->where(['chapter'=>$chapter, 'page'=>$cpage]);

            }])
            ->find($novelRequest->input('params.id'));

        if ($model) {
            return $this->respond($model->toArray());
        }

        return $this->failed(ApiStatus::CODE_1021);
    }


}