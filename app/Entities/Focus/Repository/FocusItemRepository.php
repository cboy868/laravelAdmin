<?php

namespace App\Entities\Focus\Repository;

use App\Common\ApiStatus;
use Cboy868\Repositories\Eloquent\Repository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:28
 */

class FocusItemRepository extends Repository
{
    function model()
    {
        return 'App\Entities\Focus\FocusItem';
    }


    /**
     * 删除动作 顺便删除文件
     * @param $id
     * @return mixed|void
     */
    public function trash($id)
    {
        $model = $this->model->find($id);

        if (!$model) {
            throw new \Exception('data not found', ApiStatus::CODE_1021);
        }
        try {
            Storage::disk('public')->delete($model->path);

            return $model->delete();
        } catch (\Exception $e) {
            Log::error('file_delete_error', [
                'msg' => $e->getMessage()
            ]);
            return false;
        }
    }

}