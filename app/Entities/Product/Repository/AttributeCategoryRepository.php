<?php

namespace App\Entities\Product\Repository;

use Cboy868\Repositories\Eloquent\SoftDeleteRepository;

/**
 * Class AttributeCategoryRepository
 * @package App\Entities\Product\Repository
 *
 * @link http://www.zhuo-xun.com/
 * @copyright Copyright (c) 2018 zhuoxun Software LLC
 *
 */
class AttributeCategoryRepository extends SoftDeleteRepository
{
    function model()
    {
        return 'App\Entities\Product\AttributeCategory';
    }
}