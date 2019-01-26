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

First, create your repository class. Note that your repository class MUST extend ```Bosnadev\Repositories\Eloquent\Repository``` and implement model() method

```php
<?php namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;

class FilmsRepository extends Repository {

    public function model() {
        return 'App\Film';
    }
}
```

By implementing ```model()``` method you telling repository what model class you want to use. Now, create ```App\Film``` model:

```php
<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model {

    protected $primaryKey = 'film_id';

    protected $table = 'film';

    protected $casts = [
        "rental_rate"       => 'float'
    ];
}
```

And finally, use the repository in the controller:

```php
<?php namespace App\Http\Controllers;

use App\Repositories\FilmsRepository as Film;

class FilmsController extends Controller {

    private $film;

    public function __construct(Film $film) {

        $this->film = $film;
    }

    public function index() {
        return \Response::json($this->film->all());
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
public function findWhere($where, $columns = array('*'))
```

##### Cboy868\Repositories\Contracts\CriteriaInterface

```php
public function apply($model, Repository $repository)
```

### Example usage


Create a new film in repository:

```php
$this->film->create(Input::all());
```

Update existing film:

```php
$this->film->update(Input::all(), $film_id);
```

Delete film:

```php
$this->film->delete($id);
```

Find film by film_id;

```php
$this->film->find($id);
```

you can also chose what columns to fetch:

```php
$this->film->find($id, ['title', 'description', 'release_date']);
```

Get a single row by a single column criteria.

```php
$this->film->findBy('title', $title);
```

Or you can get all rows by a single column criteria.
```php
$this->film->findAllBy('author_id', $author_id);
```

Get all results by multiple fields

```php
$this->film->findWhere([
    'author_id' => $author_id,
    ['year','>',$year]
]);
```

## Criteria

Criteria is a simple way to apply specific condition, or set of conditions to the repository query. Your criteria class MUST extend the abstract ```Cboy868\Repositories\Criteria\Criteria``` class.

Here is a simple criteria:

```php
<?php namespace App\Repositories\Criteria\Films;

use Cboy868\Repositories\Criteria\Criteria;
use Cboy868\Repositories\Contracts\RepositoryInterface as Repository;

class LengthOverTwoHours extends Criteria {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $model = $model->where('length', '>', 120);
        return $model;
    }
}
```

Now, inside you controller class you call pushCriteria method:

```php
<?php namespace App\Http\Controllers;

use App\Repositories\Criteria\Films\LengthOverTwoHours;
use App\Repositories\FilmsRepository as Film;

class FilmsController extends Controller {

    /**
     * @var Film
     */
    private $film;

    public function __construct(Film $film) {

        $this->film = $film;
    }

    public function index() {
        $this->film->pushCriteria(new LengthOverTwoHours());
        return \Response::json($this->film->all());
    }
}
```


## Credits

This package is largely inspired by [this](https://github.com/prettus/l5-repository) great package by @andersao. [Here](https://github.com/anlutro/laravel-repository/) is another package I used as reference.