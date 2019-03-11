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
use App\Services\UploadsManager;
use Illuminate\Support\Facades\Log;

class FileService
{
    protected $manager;

    protected $temp = 'tmp';

    protected $date;

    protected $rootDir = 'pictures';

    public function __construct(UploadsManager $manager)
    {
        $this->manager = $manager;
        $this->manager->setConfig('blog.uploads.storage');
        $this->date = date('Ymd');
    }


    public function sync($album, $picture)
    {
        //创建目录失败
        if (!$this->_mkdir()) return false;

        $this->_toDb($album, $picture);

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
     */
    private function _toDb($album, $picture)
    {
        $data = $this->manager->folderInfo($this->temp);

        $subfolders = $data['subfolders'];

        $baseDir = $this->rootDir . '/' . $this->date;

        foreach ($subfolders as $folder) {

            # 目录名
            $ar = explode('_', $folder);
            $category_id = 1;
            $name = $folder;
            if (count($ar) == 2) {
                $category_id = $ar[0];
                $name = $ar[1];
            }

            # 目录内文件
            $files = $this->manager->files($this->temp . '/' .$folder);
            $num = count($files['files']);

            $model = $album->create([
                'category_id' => $category_id,
                'author' => 'admin',
                'name' => $name,
                "thumb" => 0,
                "num" => $num,
                "created_by" => 1
            ]);

            $dir = $baseDir . '/' . $name;

            foreach ($files['files'] as $file) {
                $arr = explode('.', $file['name']);
                $picture->create([
                    "pictures_id" => $model->id,
                    "title" => $file['name'] ,
                    "path" => $dir,
                    "name" => $arr[0],
                    "ext" => $arr[1]
                ]);
            }
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
            $name = $folder;
            if (count($ar) == 2) {
                $name = $ar[1];
            }

            $this->manager->disk->move($this->temp . '/' . $folder, $this->date . '/' . $name);
        }
    }

}
