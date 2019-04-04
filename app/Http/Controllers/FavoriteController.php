<?php

namespace App\Http\Controllers;

use App\Common\ApiStatus;
use App\Entities\Pictures\Pictures;
use App\Http\Requests\FavoriteRequest;
use Illuminate\Http\Request;
use Cboy868\Repositories\Exceptions\RepositoryException;

class FavoriteController extends ApiController
{
    protected $model;

    /**
     * 列表
     * @return mixed
     * @throws \Exception
     */
    public function favorites()
    {
        $user = auth('member')->user();

        //未登录
        if (!$user) {
            return $this->failed(ApiStatus::CODE_2002);
        }

        try {

            $result = $user->favorites(Pictures::class)->paginate();

        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $this->respond($result);
    }


    /**
     * 添加收藏
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function favorite(FavoriteRequest $request)
    {
        $user = auth('member')->user();
        //未登录
        if (!$user) {
            return $this->failed(ApiStatus::CODE_2002);
        }

        try {
            $this->model->favorite($user, $request->input('id'));
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $this->respond([]);
    }


    /**
     * 取消收藏
     * @param $id
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function unFavorite(FavoriteRequest $request)
    {
        $user = auth('member')->user();
        //未登录
        if (!$user) {
            return $this->failed(ApiStatus::CODE_2002);
        }

        try {
            $this->model->unFavorite($user, $request->input('id'));
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $this->respond([]);
    }


}
