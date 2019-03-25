<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 22:02
 */
namespace App\Entities\Order\Creators;

interface CreaterInterface {

    /**
     * 过滤 判断是否符合条件
     * @return bool
     */
    public function filter(): bool;


    /**
     * 获取 所有数据
     * @return array
     */
    public function getData(): array;


    /**
     * 创建
     * @return bool
     */
    public function create(): bool;
}