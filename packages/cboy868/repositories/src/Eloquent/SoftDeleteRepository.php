<?php
namespace Cboy868\Repositories\Eloquent;

use App\Common\ApiStatus;
use Cboy868\Repositories\Contracts\SoftDeleteInterface;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Support\Facades\Log;

/**
 * 软删除的库可以继承此类
 * Class Repository
 * @package Cboy868\Repositories\Eloquent
 */
abstract class SoftDeleteRepository extends Repository implements SoftDeleteInterface
{
    /**
     * 只包括软删除的数据
     * @return mixed|void
     */
    public function onlyTrashed()
    {
        $this->model = $this->model->onlyTrashed();
        return $this;
    }

    /**
     * 包括所有类型数据
     * @return mixed|void
     */
    public function withTrashed()
    {
        $this->model = $this->model->withTrashed();
        return $this;
    }

    /**
     * 数据恢复
     * @param $id
     * @return mixed
     * @throws RepositoryException
     */
    public function restore($id)
    {
        $model = $this->model->onlyTrashed()->find($id);
        if (!$model) {
            throw new RepositoryException('data not found', ApiStatus::CODE_1021);
        }
        return $model->restore();
    }
    
        /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        try {
            $result = $this->model->withTrashed()->where($attribute, '=', $id)->update($data);
        } catch (QueryException $e) {

            Log::error(__METHOD__,[
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
                'file' => $e->getFile()
            ]);

            throw new RepositoryException("update failure");
        }

        return $result;
    }
}
