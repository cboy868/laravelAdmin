<?php

namespace App\Http\Controllers;

use App\Common\ApiStatus;
use App\Member;
use App\User;
use Illuminate\Http\Request;
use Cboy868\Repositories\Exceptions\RepositoryException;

class FavoriteController extends ApiController
{
    protected $model;

    /**
     * 添加收藏
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function favorite(Request $request)
    {
        $params = array_filters($request->input());

        $user = auth('member')->user();

        //未登录
        if (!$user) {
            return $this->failed(ApiStatus::CODE_2002);
        }

        try {
            $this->model->favorite($user, $params['id']);
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
    public function unFavorite(Request $request)
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
