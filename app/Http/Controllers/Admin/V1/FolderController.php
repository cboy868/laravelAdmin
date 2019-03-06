<?php

namespace App\Http\Controllers\Admin\V1;

use App\Common\ApiStatus;
use App\Entities\Pictures\Repository\CategoryRepository;
use App\Entities\Pictures\Repository\PicturesItemRepository;
use App\Entities\Pictures\Repository\PicturesRepository;
use App\Entities\Pictures\Services\FileService;
use App\Http\Requests\UploadNewFolderRequest;
use App\Services\UploadsManager;
use Illuminate\Http\Request;

class FolderController extends AdminController
{
    public $manager;

    public function __construct(UploadsManager $manager)
    {
        $this->manager = $manager;
    }


    /**
     * 初始化目录
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request,
                          PicturesRepository $picture,
                          CategoryRepository $category,
                          PicturesItemRepository $item, FileService $fileService)
    {
        $fileService->sync($picture, $item);
        die;



        $dir = $request->get('folder', '/');

        $data = $this->manager->folderInfo($dir);

        $subfolders = $data['subfolders'];

        foreach ($subfolders as $folder) {

            # 目录名
            $ar = explode('_', $folder);
            $category_id = 1;
            $name = $folder;
            if (count($ar) == 2) {
                $category_id = $ar[0];
            }

            # 目录内文件
            $files = $this->manager->files($folder);
            $num = count($files);

            $model = $picture->create([
                'category_id' => $category_id,
                'author' => 'admin',
                'name' => $name,
                "thumb" => 0,
                "num" => $num,
                "created_by" => 1
            ]);

            foreach ($files['files'] as $file) {
                $arr = explode('.', $file['name']);
                $item->create([
                    "pictures_id" => $model->id,
                    "title" => $file['name'] ,
                    "path" => $dir,
                    "name" => $arr[0],
                    "ext" => $arr[1],
                ]);
            }

        }

        return $this->respond($subfolders);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index1(Request $request)
    {
        $folder = $request->get('folder');
        $data = $this->manager->folderInfo($folder);

        return $this->respond($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadNewFolderRequest $request)
    {
        $new_folder = $request->get('new_folder');
        $folder = $request->get('folder').'/'.$new_folder;

        try {
            $result = $this->manager->createDirectory($folder);
            if ($result === true) {
                return $this->respond();
            }
        } catch (\Exception $e) {
            return $this->failed(ApiStatus::CODE_3051);
        }

        return $this->failed(ApiStatus::CODE_3051);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $del_folder = $request->get('del_folder');
        $folder = $request->get('folder').'/'.$del_folder;

        $result = $this->manager->deleteDirectory($folder);

        if ($result === true) {
            return $this->respond();
        }
        return $this->failed(ApiStatus::CODE_3052);
    }
}
