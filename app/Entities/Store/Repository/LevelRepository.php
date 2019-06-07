<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/7
 * Time: 21:40
 */

namespace App\Entities\Store\Repository;


use Cboy868\Repositories\Eloquent\Repository;

class LevelRepository extends Repository
{
    function model()
    {
        return 'App\Entities\Store\Level';
    }
}