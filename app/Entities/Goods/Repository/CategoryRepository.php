<?php

namespace App\Entities\Goods\Repository;

use Cboy868\Repositories\Eloquent\SoftDeleteRepository;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Support\Facades\Log;

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:28
 */

class CategoryRepository extends SoftDeleteRepository
{
    function model()
    {
        return 'App\Entities\Goods\Category';
    }

    /**
     * @param array $data
     * @return mixed
     */
//    public function create(array $data)
//    {
//        try {
//            $model = $this->model->create($data);
//        } catch (QueryException $e) {
//
//            Log::error(__METHOD__,[
//                'code' => $e->getCode(),
//                'msg' => $e->getMessage(),
//                'file' => $e->getFile()
//            ]);
//
//            throw new RepositoryException("Create failure");
//        }
//
//        return $model;
//    }


//    public function update(array $data, $id, $attribute = "id")
//    {
//        try {
//            $result = $this->model->where($attribute, '=', $id)->update($data);
//        } catch (QueryException $e) {
//
//            Log::error(__METHOD__,[
//                'code' => $e->getCode(),
//                'msg' => $e->getMessage(),
//                'file' => $e->getFile()
//            ]);
//
//            throw new RepositoryException("update failure");
//        }
//
//        return $result;
//    }
}