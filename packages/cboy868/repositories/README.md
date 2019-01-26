# Laravel Repositories


## Installation

Run the following command from you terminal:


 ```bash
 composer require "cboy868/repositories: dev-master"
 ```

or add this to require section in your composer.json file:

 ```
 "cboy868/repositories": "dev-master"
 ```

then run ```composer update```

## Usage

First, create your repository class. Note that your repository class MUST extend ```Cboy868\Repositories\Eloquent\Repository``` and implement model() method

```php
<?php 

namespace App\Repository;
use Cboy868\Repositories\Eloquent\Repository;

class PostRepository extends Repository
{
    function model()
    {
        return 'App\Models\Post';
    }
}
```

By implementing ```model()``` method you telling repository what model class you want to use. Now, create ```App\post``` model:

```php
<?php 
namespace App\Models;

use App\Concern\Likeable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use Likeable;

    /**
     * 状态
     */
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = -1;
    const STATUS_VERIFYING = 0;

    protected $table = 'post';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'title',
        'content',
        'posted_at',
        'thumbnail_id',
        'status'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'posted_at'
    ];

    public function scopeWithOnly(Builder $query, $relation, Array $columns)
    {
        return $query->with([$relation => function ($query) use ($columns){
            $query->select(array_merge(['id'], $columns));
        }]);
    }

    /**
     * Return the post's author
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
```

And finally, use the repository in the controller:

```php
<?php
namespace App\Http\Controllers\Admin\V1;

use App\Common\ApiStatus;
use App\Http\Controllers\ApiController;
use App\Repository\Criteria\Status;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Response;
use App\Repository\PostRepository as Post;

class PostController extends ApiController
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function index(Request $request)
    {
        
        $pageLevel = $request->input('params.page_size', self::PAGE_SIZE_TWO);
        $pageSize = isset(self::$pageSize[$pageLevel]) ? self::$pageSize[$pageLevel] : 25;

        // $result = PostResource::collection(
        //     $this->post->where([
        //         ['title', 'like', 'the-%']
        //     ])->orderBy('id', 'DESC')->with('author')
        //         ->paginate($pageSize)
        // );

        //或者
        $result = $this->post->where([
                ['title', 'like', 'the-%']
            ])
        ->orderBy('id', 'DESC')
        ->with('author')
        ->paginate($pageSize);

        return $this->respond($result);
    }
}
```

## Available Methods

The following methods are available:

##### Cboy868\Repositories\Contracts\RepositoryInterface

```php
public function all($columns = array('*'))
public function lists($value, $key = null)
public function paginate($perPage = 1, $columns = array('*'));
public function create(array $data)
// if you use mongodb then you'll need to specify primary key $attribute
public function update(array $data, $id, $attribute = "id")
public function delete($id)
public function find($id, $columns = array('*'))
public function findBy($field, $value, $columns = array('*'))
public function findAllBy($field, $value, $columns = array('*'))
//chaining call
public function where($where, $or=false)
public function with($relations)
//定义要取的字段
public function withOnly($relations, Array $columns)

```

##### Cboy868\Repositories\Contracts\CriteriaInterface

```php
public function apply($model, Repository $repository)
```

### Example usage


Create a new post in repository:

```php
$this->post->create(Input::all());
```

Update existing post:

```php
$this->post->update(Input::all(), $post_id);
```

Delete post:

```php
$this->post->delete($id);
```

Find post by post_id;

```php
$this->post->find($id);
```

you can also chose what columns to fetch:

```php
$this->post->find($id, ['title', 'description', 'release_date']);
```

Get a single row by a single column criteria.

```php
$this->post->findBy('title', $title);
```

Or you can get all rows by a single column criteria.
```php
$this->post->findAllBy('author_id', $author_id);
```

## Criteria

Criteria is a simple way to apply specific condition, or set of conditions to the repository query. Your criteria class MUST extend the abstract ```Cboy868\Repositories\Criteria\Criteria``` class.

Here is a simple criteria:

```php
<?php 
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
```

Now, inside you controller class you call pushCriteria method:

```php
<?php namespace App\Http\Controllers;

use App\Repositories\Criteria\posts\LengthOverTwoHours;
use App\Repositories\postsRepository as post;

class postsController extends Controller {

    /**
     * @var post
     */
    private $post;

    public function __construct(post $post) {

        $this->post = $post;
    }

    public function index() {
        $model = $this->post->model();
        //$model::STATUS_ACTIVE active posts
        $this->post->pushCriteria(new Status($model::STATUS_ACTIVE));

        return \Response::json($this->post->all());
    }
}
```