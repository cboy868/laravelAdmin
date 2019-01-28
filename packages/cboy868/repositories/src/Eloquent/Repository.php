<?php
namespace Cboy868\Repositories\Eloquent;

use App\Common\ApiStatus;
use Illuminate\Container\Container as App;
use Cboy868\Repositories\Contracts\RepositoryInterface;
use Cboy868\Repositories\Exceptions\RepositoryException;
use Cboy868\Repositories\Contracts\CriteriaInterface;
use Cboy868\Repositories\Criteria\Criteria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

/**
 * Class Repository
 * @package Cboy868\Repositories\Eloquent
 */
abstract class Repository implements RepositoryInterface, CriteriaInterface
{
    private $app;
    protected $model;
    protected $newModel;


    /**
     * @var Collection
     */
    protected $criteria;
    /**
     * @var bool
     */
    protected $skipCriteria = false;
    /**
     * Prevents from overwriting same criteria in chain usage
     * @var bool
     */
    protected $preventCriteriaOverwriting = true;
    /**
     * @param App $app
     * @param Collection $collection
     */
    public function __construct(App $app, Collection $collection)
    {
        $this->app = $app;
        $this->criteria = $collection;
        $this->resetScope();
        $this->makeModel();
    }
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public abstract function model();
    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->get($columns);
    }
    /**
     * @param array $relations
     * @return $this
     */
    public function with($relations)
    {
        $this->model = $this->model->with($relations);
        return $this;
    }

    /**
     * 取固定字段
     * @param $relations
     * @param array $columns
     * @return mixed
     */
    public function withOnly($relations, Array $columns)
    {
        return $this->model->withOnly($relations, $columns);
    }
    /**
     * @param  string $value
     * @param  string $key
     * @return array
     */
    public function lists($value, $key = null)
    {
        $this->applyCriteria();
        $lists = $this->model->lists($value, $key);
        if (is_array($lists)) {
            return $lists;
        }
        return $lists->all();
    }
    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 25, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->paginate($perPage, $columns);
    }
    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        try {
            $model = $this->model->create($data);
        } catch (QueryException $e) {

            Log::error(__METHOD__,[
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
                'file' => $e->getFile()
            ]);

            throw new RepositoryException("Create failure");
        }

        return $model;
    }
    /**
     * save a model without massive assignment
     *
     * @param array $data
     * @return bool
     */
    public function saveModel(array $data)
    {
        foreach ($data as $k => $v) {
            $this->model->$k = $v;
        }

        try {
            $result = $this->model->save();
        } catch (QueryException $e) {

            Log::error(__METHOD__,[
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
                'file' => $e->getFile()
            ]);

            throw new RepositoryException("SaveModel failure");
        }

        return $result;
    }
    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        try {
            $result = $this->model->where($attribute, '=', $id)->update($data);
        } catch (QueryException $e) {

            Log::error(__METHOD__,[
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
                'file' => $e->getFile()
            ]);

            throw new RepositoryException("update failure");
        }

        return $result;
    }
    /**
     * @param  array $data
     * @param  $id
     * @return mixed
     */
    public function updateRich(array $data, $id)
    {
        if (!($model = $this->model->find($id))) {
            return false;
        }

        try {
            $result = $model->fill($data)->save();
        } catch (QueryException $e) {

            Log::error(__METHOD__,[
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
                'file' => $e->getFile()
            ]);

            throw new RepositoryException("updateRich failure");
        }

        return $result;
    }
    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {

        try {
            $result = $this->model->destroy($id);
        } catch (QueryException $e) {
            Log::error(__METHOD__,[
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
                'file' => $e->getFile()
            ]);

            throw new RepositoryException("Destroy failure");
        }

        return $result;
    }
    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->find($id, $columns);
    }
    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->where($attribute, '=', $value)->first($columns);
    }
    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findAllBy($attribute, $value, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->where($attribute, '=', $value)->get($columns);
    }

    /**
     * make a collection of models by the given query conditions and return self.
     * @param array $where
     * @param bool $or
     * @return $this
     */
    public function where(Array $where, $or = false)
    {
        $model = $this->model;
        foreach ($where as $field => $value) {
            if ($value instanceof \Closure) {
                $model = (!$or)
                    ? $model->where($value)
                    : $model->orWhere($value);
            } elseif (is_array($value)) {
                if (count($value) === 3) {
                    list($field, $operator, $search) = $value;
                    $model = (!$or)
                        ? $model->where($field, $operator, $search)
                        : $model->orWhere($field, $operator, $search);
                } elseif (count($value) === 2) {
                    list($field, $search) = $value;
                    $model = (!$or)
                        ? $model->where($field, '=', $search)
                        : $model->orWhere($field, '=', $search);
                }
            } else {
                $model = (!$or)
                    ? $model->where($field, '=', $value)
                    : $model->orWhere($field, '=', $value);
            }
        }

        $this->model = $model;
        return $this;
    }

    /**
     * 为查询结果排序
     * @param $field
     * @param $sort
     * @return $this
     */
    public function orderBy($field, $sort)
    {
        $this->model = $this->model->orderBy($field, $sort);

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws RepositoryException
     */
    public function makeModel()
    {
        return $this->setModel($this->model());
    }

    /**
     * Set Eloquent Model to instantiate
     *
     * @param $eloquentModel
     * @return Model
     * @throws RepositoryException
     */
    public function setModel($eloquentModel)
    {
        $this->newModel = $this->app->make($eloquentModel);

        if (!$this->newModel instanceof Model)
            throw new RepositoryException("Class {$this->newModel} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        return $this->model = $this->newModel;
    }

    /**
     * @return $this
     */
    public function resetScope()
    {
        $this->skipCriteria(false);
        return $this;
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);
        return $this;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria)
    {
        if ($this->preventCriteriaOverwriting) {
            // Find existing criteria
            $key = $this->criteria->search(function ($item) use ($criteria) {
                return (is_object($item) && (get_class($item) == get_class($criteria)));
            });
            // Remove old criteria
            if (is_int($key)) {
                $this->criteria->offsetUnset($key);
            }
        }
        $this->criteria->push($criteria);
        return $this;
    }

    /**
     * @return $this
     */
    public function applyCriteria()
    {
        if ($this->skipCriteria === true)
            return $this;
        foreach ($this->getCriteria() as $criteria) {
            if ($criteria instanceof Criteria)
                $this->model = $criteria->apply($this->model, $this);
        }
        return $this;
    }


    /**
     * 软删除动作
     * @param $id
     * @return mixed|void
     */
    public function trash($id)
    {
        $model = $this->model->find($id);

        if (!$model) {
            throw new RepositoryException('data not found', ApiStatus::CODE_1021);
        }

        return $model->delete();
    }

    /**
     * 只包括软删除的数据
     * @return mixed|void
     */
    public function onlyTrashed()
    {
        $this->model->onlyTrashed();
        return $this;
    }

    /**
     * 包括所有类型数据
     * @return mixed|void
     */
    public function withTrashed()
    {
        $this->model->withTrashed();
        return $this;
    }

    /**
     * 数据恢复
     * @param $id
     * @return mixed
     * @throws RepositoryException
     */
    public function restore($id)
    {
        $model = $this->model->onlyTrashed()->find($id);

        if (!$model) {
            throw new RepositoryException('data not found', ApiStatus::CODE_1021);
        }

        return $model->restore();
    }
}