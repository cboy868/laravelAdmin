<?php
/**
 * Created by PhpStorm.
 * User: wansq
 * Date: 2019/3/3
 * Time: 19:59
 */
namespace App\Traits;

trait scopeModel{
    public function scopeWithOnly($query, $relation, Array $columns)
    {
        return $query->with([$relation => function ($query) use ($columns){
            $query->select(array_merge(['id'], $columns));
        }]);
    }
}