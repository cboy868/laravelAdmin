<?php
namespace Cboy868\Repositories\Contracts;

/**
 * Interface RepositoryInterface
 * @package Cboy868\Repositories\Contracts
 */
interface RepositoryInterface
{
    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'));

    /**
     * @param $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 1, $columns = array('*'));

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param array $data
     * @return bool
     */
    public function saveModel(array $data);

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'));

    /**
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($field, $value, $columns = array('*'));

    /**
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findAllBy($field, $value, $columns = array('*'));

    /**
     * 增加查找条件 并返回自身  链式调用
     * @param $where
     * @return mixed
     */
    public function where(Array $where, $or=false);

    /**
     * 排序 并返回自身  链式调用
     * @param $field
     * @param $sort
     * @return mixed
     */
    public function orderBy($field, $sort);


    ///////////////////////////////////////////////////////
    /// 软删除相应方法
    /// ///////////////////////////////////////////////////

    /**
     * 软删除
     * @param $id
     * @return mixed
     */
    public function trash($id);

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