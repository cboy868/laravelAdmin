<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/7
 * Time: 22:17
 */

namespace App\Entities\Product\Repository;


use Cboy868\Repositories\Eloquent\SoftDeleteRepository;

class BrandRepository extends SoftDeleteRepository
{
    function model()
    {
        return 'App\Entities\Product\Brand';
    }
}