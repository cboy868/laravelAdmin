<?php
namespace Cboy868\Repositories\Criteria;

use Cboy868\Repositories\Contracts\RepositoryInterface as Repository;
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/25
 * Time: 17:06
 */
abstract class Criteria {
    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public abstract function apply($model, Repository $repository);
}