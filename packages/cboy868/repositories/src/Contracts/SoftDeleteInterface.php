<?php
namespace Cboy868\Repositories\Contracts;

/**
 * Interface RepositoryInterface
 * @package Cboy868\Repositories\Contracts
 */
interface SoftDeleteInterface
{


    /**
     * 获取软删除的数据
     * @return mixed
     */
    public function onlyTrashed();

    /**
     * 包括软删除和未删除的所有符合条件数据
     * @return mixed
     */
    public function withTrashed();

    /**
     * 数据恢复有效
     * @return mixed
     */
    public function restore($id);

}