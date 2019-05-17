<?php

namespace App\Http\Controllers\Admin\V1\Cms;

use App\Common\ApiStatus;
use App\Entities\Focus\Repository\FocusItemRepository;
use App\Entities\Focus\Repository\FocusRepository;
use App\Entities\Focus\Requests\FocusRequest;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Cboy868\Repositories\Exceptions\RepositoryException;

class FocusController extends ApiController
{
    public $focusRepository;

    public $focusItemRepository;

    public function __construct(FocusRepository $focusRepository, FocusItemRepository $focusItemRepository)
    {
        $this->focusRepository = $focusRepository;

        $this->focusItemRepository = $focusItemRepository;

        parent::__construct();
    }

    /**
     * 图片上传
     */
    public function upload(Request $request)
    {

        $id = $request->input('id');

        if (!$id) {
            return $this->failed(ApiStatus::CODE_1001);
        }

        $path = $request->file('focus')->store(
            'focus', 'public'
        );

        $model = $this->focusItemRepository->create([
            'fid' => $id,
            'path' => $path,
            'link' => '',
            'title' => '',
            'intro' => '',
        ]);

        return $this->respond([
            'model' => $model->toArray(),
            'path' =>  'http://' . \request()->getHttpHost() . '/storage/' . $path,
            'params' => $request->input()
        ]);
    }
}
