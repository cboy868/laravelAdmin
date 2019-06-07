<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/7
 * Time: 21:41
 */

namespace App\Entities\Store\Repository;


use Cboy868\Repositories\Eloquent\Repository;

class CostRespository extends Repository
{
    function model()
    {
        return 'App\Entities\Store\Cost';
    }
}