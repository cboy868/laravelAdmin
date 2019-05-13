<?php

namespace App\Http\Controllers\Admin\V1\Cms;

use App\Common\ApiStatus;
use App\Entities\Pictures\Repository\CartoonRepository;
use App\Entities\Pictures\Repository\PicturesUserRelRepository;
use App\Entities\Pictures\Requests\StorePicturesRequest;
use App\Entities\Pictures\Requests\UpdatePicturesRequest;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use App\Entities\Pictures\Repository\PicturesRepository;
use Cboy868\Repositories\Exceptions\RepositoryException;

class CartoonController extends AdminController
{
    protected $picturesRepository;

    protected $cartoonRepository;

    public function __construct(PicturesRepository $picturesRepository, CartoonRepository $cartoonRepository)
    {
        $this->picturesRepository = $picturesRepository;

        $this->cartoonRepository = $cartoonRepository;

        parent::__construct();
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


    /**
     * 所有章节
     */
    public function chapter($id, CartoonRepository $cartoon, PicturesUserRelRepository $picturesUserRelRepository)
    {
        $auth = 0;
        if ($user = auth('member')->user()) {
            $rel = $picturesUserRelRepository->where(['user_id' => $user->id, 'pictures_id' => $id])->first();
            $auth = $rel ? 1 : 0;
        }

        $model = $cartoon->find($id);

        if (!$model) {
            return $this->failed(ApiStatus::CODE_1021);
        }

        $result = $model->toArray();

        $baseUrl = 'http://' . \request()->getHttpHost() . '/storage/';
        $result['base_url'] = $baseUrl;

        if ($auth || $model->chapter < 3) {
            $result['items'] = $model->items;
            return $this->respond($result);
        }

        //未购买提示，需要购买
        return $this->failed(ApiStatus::CODE_4004);
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

        if (!is_numeric($id)) {
            return $this->failed(ApiStatus::CODE_1001);
        }

        $params = array_filters($request->input());
        try {
            unset($params['_method']);
            $z = $this->cartoonRepository->update($params, $id);

        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
            return $this->failed(ApiStatus::CODE_1011);
        }
        return $this->respond([$z]);
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
            $result = $this->cartoonRepository->trash($id);
        } catch (RepositoryException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this->respond($result);
    }
}
