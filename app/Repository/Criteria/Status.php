<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/1/26
 * Time: 11:03
 */

namespace App\Repository\Criteria;


use App\Models\Post;
use Cboy868\Repositories\Criteria\Criteria;
use Cboy868\Repositories\Contracts\RepositoryInterface as Repository;

class Status extends Criteria
{
    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function apply($model, Repository $repository)
    {
        $model = $model->where('status', '=', $this->status);
        return $model;
    }
}