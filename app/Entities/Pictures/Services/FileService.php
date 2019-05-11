<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/18
 * Time: 10:38
 */

namespace App\Entities\Pictures\Services;


use App\Common\ApiStatus;
use App\Entities\Pictures\Repository\CartoonRepository;
use App\Services\UploadsManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Entities\Pictures\Repository\PicturesItemRepository;
use App\Entities\Pictures\Repository\PicturesRepository;

class FileService
{
    protected $manager;

    protected $picturesRepository;

    protected $picturesItemRepository;

    protected $cartoonRepository;

    protected $temp = 'tmp';

    protected $date;

    protected $rootDir = 'pictures';

    public function __construct(UploadsManager $manager,
                                PicturesRepository $picturesRepository,
                                CartoonRepository $cartoonRepository,
                                PicturesItemRepository $picturesItemRepository)
    {
        $this->manager = $manager;
        $this->cartoonRepository = $cartoonRepository;
        $this->picturesRepository = $picturesRepository;
        $this->picturesItemRepository = $picturesItemRepository;
        $this->manager->setConfig('blog.uploads.storage');
        $this->date = date('Ymd');
    }

    public function sync()
    {
        //创建目录失败
        if (!$this->_mkdir()) return false;

        $this->_toDb();

        $this->_move();
    }


    /**
     * 模糊处理
     */
    public function blur()
    {
        $file = storage_path('app/public/pictures/tmp/2.png');

        $img = $this->manager->make($file);
        $img->blur(30);//0-100
        $img->save(storage_path('app/public/pictures/tmp/2blur.png'));
    }


    /**
     * 创建目录
     */
    private function _mkdir()
    {
        $dir = $this->date;
        try {
            $this->manager->createDirectory($dir);
        } catch (\Exception $e) {

            Log::error(__METHOD__ . __LINE__, [
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
                'dir' => $dir
            ]);

            if ($e->getCode() == ApiStatus::CODE_3055) {
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * 入库
     * 文件目录格式 分类category_名称name 在tmp目录下面 5_name，6_name  5,6指分类，name是图集名
     *
     */
    private function _toDb()
    {
        $data = $this->manager->folderInfo($this->temp);

        $subfolders = $data['subfolders'];

        foreach ($subfolders as $folder) {

            # 目录名
            $ar = explode('_', $folder);
            if (count($ar) != 2) {
                return false;
            }

            if ($ar[0] == 1) {
                $this->_handelPicture($folder);
            } elseif ($ar[0] == 2) {
                $this->_handleCartoon($folder);
            }
        }
    }

    private function _handelPicture($folder)
    {
        $baseDir = $this->rootDir . '/' . $this->date;
        $ar = explode('_', $folder);
        try {

            $category_id = 1;
            $name = $ar[1];
//            $category_id = $ar[1];
//            $name = $ar[2];

            # 目录内文件
            $files = $this->manager->files($this->temp . '/' . $folder);
            $num = count($files['files']);

            $model = $this->picturesRepository->create([
                'category_id' => $category_id,
                'author' => 'admin',
                'name' => $name,
                "thumb" => 0,
                "num" => $num,
                "created_by" => 1,
                "type" => 1
            ]);

            $picturesData = [];

            $dir = $baseDir . '/' . $name;
            $tmpDir = $this->temp . '/' . $folder;
            foreach ($files['files'] as $file) {
                $arr = explode('.', $file['name']);
                //需要先把文件名改为英文,title 存原名
                $newName = uniqid();
                $this->manager->disk->move($tmpDir . '/' . $file['name'], $tmpDir . '/' . $newName . '.' . $arr[1]);

                $picturesData[] = [
                    "pictures_id" => $model->id,
                    "title" => $arr[0],
                    "path" => $dir,
                    "name" => $newName,
                    "ext" => $arr[1]
                ];
            }


            DB::table('pictures_item')->insert($picturesData);

        } catch (\Exception $e) {

        }
    }

    private function _handleCartoon($folder)
    {
        $baseDir = $this->rootDir . '/' . $this->date;
        $ar = explode('_', $folder);
        DB::beginTransaction();
        try {
            $picture_num = 0;
            $category_id = 1;
            $name = $ar[1];
//            $category_id = $ar[1];
//            $name = $ar[2];

            $chartFolders = $this->manager->folderInfo($this->temp . '/' . $folder);

            $picturesData = [];

            $model = $this->picturesRepository->create([
                'category_id' => $category_id,
                'author' => 'admin',
                'name' => $name,
                "thumb" => 0,
                "num" => 0,
                "created_by" => 1,
                "type" => 2
            ]);


            if (count($chartFolders['subfolders']) > 0) {
                $chapter = 1;
                foreach ($chartFolders['subfolders'] as $chartFolder) {
                    $files = $this->manager->files($this->temp . '/' . $folder . '/' . $chartFolder);

                    $picture_num += count($files['files']);

                    $charterModel = $this->cartoonRepository->create([
                        "pictures_id" => $model->id,
                        "chapter" => $chapter,
                        "title" => $chartFolder,
                        "subtitle" => "",
                        "thumb" => "",
                    ]);
                    $chapter++;

                    $dir = $baseDir . '/' . $name . '/' . $chartFolder;
                    $tmpDir = $this->temp . '/' . $folder . '/' . $chartFolder;
                    foreach ($files['files'] as $file) {
                        $arr = explode('.', $file['name']);
                        //需要先把文件名改为英文,title 存原名
                        $newName = uniqid();
                        $this->manager->disk->move($tmpDir . '/' . $file['name'], $tmpDir . '/' . $newName . '.' . $arr[1]);

                        $picturesData[] = [
                            "pictures_id" => $model->id,
                            "cartoon_id" => $charterModel->id,
                            "title" => $arr[0],
                            "path" => $dir,
                            "name" => $newName,
                            "ext" => $arr[1]
                        ];
                    }



                }
            } else if (count($chartFolders['subfolders']) == 0 && count($chartFolders['files'] > 0)) {
                $dir = $baseDir . '/' . $name;
                $picture_num = count($chartFolders['files']);
                $tmpDir = $this->temp . '/' . $folder;
                $chapter = 1;
                foreach ($chartFolders['files'] as $file) {
                    $arr = explode('.', $file['name']);
                    //需要先把文件名改为英文,title 存原名
                    $newName = uniqid();

                    $charterModel = $this->cartoonRepository->create([
                        "pictures_id" => $model->id,
                        "chapter" => $chapter,
                        "title" => $arr[0],
                        "subtitle" => "",
                        "thumb" => $dir . '/' . $newName . '.' . $arr[1],
                    ]);
                    $chapter++;


                    $this->manager->disk->move($tmpDir . '/' . $file['name'], $tmpDir . '/' . $newName . '.' . $arr[1]);
                    $picturesData[] = [
                        "pictures_id" => $model->id,
                        "cartoon_id" => $charterModel->id,
                        "title" => $arr[0],
                        "path" => $dir,
                        "name" => $newName,
                        "ext" => $arr[1]
                    ];

                }

            }

            $this->picturesRepository->update(['num' => $picture_num], $model->id);
            DB::table('pictures_item')->insert($picturesData);

            DB::commit();
        } catch (\Exception $e) {

            Log::error(__METHOD__, [
                'msg' => $e->getMessage()
            ]);
            DB::rollBack();
        }
    }



    /**
     * 移动目录
     */
    private function _move()
    {
        $data = $this->manager->folderInfo($this->temp);

        foreach ($data['subfolders'] as $folder) {

            # 目录名
            $ar = explode('_', $folder);
            if (count($ar) != 2) {
                throw new \Exception('目录名错误');
            }

            $this->manager->disk->move($this->temp . '/' . $folder, $this->date . '/' . $ar[1]);
        }
    }

}
