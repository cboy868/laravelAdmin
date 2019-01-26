<?php
namespace Cboy868\Repositories\Criteria;

use Cboy868\Repositories\Contracts\RepositoryInterface as Repository;

/**
 * Class Criteria
 * @package Cboy868\Repositories\Criteria
 */
abstract class Criteria {

    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public abstract function apply($model, Repository $repository);
}