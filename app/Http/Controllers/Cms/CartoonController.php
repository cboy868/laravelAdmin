<?php

namespace App\Http\Controllers\Cms;

use App\Common\ApiStatus;
use App\Entities\Pictures\PicturesRel;
use App\Entities\Pictures\Repository\CartoonRepository;
use App\Entities\Pictures\Repository\PicturesUserRelRepository;
use App\Entities\Pictures\Repository\UserRepository;
use App\Entities\Pictures\Requests\StorePicturesRequest;
use App\Entities\Pictures\Requests\UpdatePicturesRequest;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Entities\Pictures\Repository\PicturesRepository;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartoonController extends ApiController
{
    public $model;

    public function __construct(PicturesRepository $model)
    {
        $this->model = $model;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        self::DEFAULT_PAGE_SIZE

        $pageSize = $request->input('page_size', 5);

        //查询条件
        $where = [];
        if ($name = $request->input('name')) {
            array_push($where, ['name', 'like', '%' . $name . '%']);
        }

        if ($cid = $request->input('cid')) {
            array_push($where, ['category_id', $cid]);
        }


        if ($flag = $request->input('flag')) {
            array_push($where, ['flag', $flag]);
        }

        array_push($where, ['type' => PicturesRepository::TYPE_CARTOON]);

        $result = $this->model->where($where)
            ->withOnly('createdby', ['name', 'email'])
            ->with('cover')
            ->with('category')
//            ->with('cartoons')
            ->with(['items' => function ($query) {
                return $query->take(3);
            }])
            ->whereHas('category', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderBy('id', 'desc')
            ->paginate($pageSize);

        if (!$result) {
            return $this->failed(ApiStatus::CODE_1021);
        }


        $res = $result->toArray();
        $baseUrl = 'http://' . \request()->getHttpHost() . '/storage/';
        $res['base_url'] = $baseUrl;

        return $this->respond($res);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePicturesRequest $request)
    {
        $params = array_filters($request->input());
        try {
            $params['created_by'] = auth('admin')->user()->id;
            $model = $this->model->create($params);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $this->respond($model->toArray());
    }


    public function chapter($id, CartoonRepository $cartoon, PicturesUserRelRepository $picturesUserRelRepository)
    {
        $model = $cartoon->find($id);

        if (!$model) {
            return $this->failed(ApiStatus::CODE_1021);
        }

        $auth = 0;
        if ($user = auth('member')->user()) {
            $rel = $picturesUserRelRepository->where(['user_id' => $user->id, 'pictures_id' => $model->pictures_id])->first();
            $auth = $rel ? 1 : 0;

            if ($auth) {
                DB::table('pictures_user_rel')
                    ->where(['user_id' => $user->id, 'pictures_id' => $rel->pictures_id])
                    ->update(['chapter_id' => $id]);
            }
        }

        $result = $model->toArray();

        $baseUrl = 'http://' . \request()->getHttpHost() . '/storage/';
        $result['base_url'] = $baseUrl;


        $nextChapter = $cartoon->where(['pictures_id' => $model->pictures_id, 'chapter' => $model->chapter + 1])->first();
        $preChapter = $cartoon->where(['pictures_id' => $model->pictures_id, 'chapter' => $model->chapter - 1])->first();

        $result['next_chapter'] = $nextChapter ? $nextChapter->id : 0;
        $result['pre_chapter'] = $preChapter ? $preChapter->id : 0;

        $max = $cartoon->where(['pictures_id'=> $model->pictures_id])->max('chapter');
        $result['title_nu'] = $model->chapter . '/' . $max;

        if ($auth || $model->chapter < 3) {
            $result['items'] = $model->items;
            return $this->respond($result);
        }

        //未购买提示，需要购买
        return $this->setCodeMsg("您需要先购买此漫画")->failed(ApiStatus::CODE_4004);
    }

    /**
     * Display the specified resource.
     * 列出所有章节
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, PicturesUserRelRepository $picturesUserRelRepository)
    {
        $auth = 0;
        if ($user = auth('member')->user()) {
            $rel = $picturesUserRelRepository->where(['user_id' => $user->id, 'pictures_id' => $id])->first();
            $auth = $rel ? 1 : 0;
        }

        $model = $this->model->where(['type' => PicturesRepository::TYPE_CARTOON])
            ->with('cartoons')
            ->with('cover')
            ->find($id);

        //查找历史记录
        $nowToRead = $model->cartoons[0]->id;
        if ($auth) {
            $chapter = 0;
            $chapters = $model->cartoons;
            $nowToRead = $rel->chapter_id;
            foreach ($chapters as $c) {
                if ($c->id == $rel->chapter_id) {
                    $chapter = $c->chapter;
                }

                if ($chapter + 1 == $c->chapter) {
                    $nowToRead = $c->id;
                }
            }
        }

        if ($model) {
            $result = $model->toArray();
            $result['read_chapter_history'] = $nowToRead;

            foreach ($result['cartoons'] as $k => &$v) {
                if ($auth) {
                    $v['auth'] = $auth;
                } else {
                    if ($k < 2) {
                        $v['auth'] = 1;
                    } else {
                        $v['auth'] = $auth;
                    }
                }
            }
            unset($v);

            $baseUrl = 'http://' . \request()->getHttpHost() . '/storage/';
            $result['base_url'] = $baseUrl;

            return $this->respond($result);
        }

        return $this->failed(ApiStatus::CODE_1021);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePicturesRequest $request, $id)
    {
        $params = array_filters($request->input());
        try {
            unset($params['_method']);
            $this->model->update($params, $id);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond([]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $result = $this->model->trash($id);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond($result);
    }
}
