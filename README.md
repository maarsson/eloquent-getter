# Laravel model repository pattern

This package adds and extendable repository pattern to your Laravel project.

Though using repository pattern over Eloquent models may be an outworn idea, you can make your Laravel project code clean with a bunch of built-in functions of this package.


## Installation

1. Add package to your Laravel project: `composer require maarsson/laravel-repository`
2. Publish config file `php artisan vendor:publish --tag=repository-config`


## Usage

1. Create repository to your existing Eloquent model: `php artisan make:repository 'YourModel'`.

2. Add your model name to the `config/repository.php`:
    ```php
        'models' => [
            'YourModel',
        ],
    ```

3. Use dependency injection in your code:
    ```php
    use App\Interfaces\YourModelRepositoryInterface;

    class TestController extends Controller
    {
        private $repository;

        public function __construct(YourModelRepositoryInterface $repository)
        {
            $this->repository = $repository;
        }
    }
    ```

3. Optionally you can add custom methods to the repository:
    ```php
        class YourModelRepository extends AbstractEloquentRepository implements YourModelRepositoryInterface
        {
            public function doSomeConverting()
            {
                // your code here
            }
        }
    ```
    and also to the interface class:
    ```php
        interface YourModelRepositoryInterface
        {
            public function doSomeConverting();
        }
    ```


## Methods

#### Retrieving entities

Retrieving all entities from the database
```php
$collection = $this->repository->all();
```

Retrieving entity by ID
```php
$entity = $this->repository->find(3);
```

Retrieving entities by a specific column
```php
$collection = $this->repository->findBy('title', 'Music');
```

Retrieving the first or last entity (by its timestamp)
```php
$entity = $this->repository->first();
$entity = $this->repository->last();
```

You can also specify which columns to be fetched
```php
$collection = $this->repository->all('id', 'title');
$collection = $this->repository->find(3, 'id', 'title');
$entity = $this->repository->first('id', 'title');
$entity = $this->repository->last('id', 'title');
```

Retrieving the number of entities
```php
$count = $this->repository->count();
```

#### Creating an entity

```php
$entity = $this->repository->create([
    'title' => 'Music',
    'price' => 12.9,
]);
```


#### Updating an entity

Updating an entity by ID

```php
$entity = $this->repository->update(
    3, // the id of the entity to be updated
    [
        'title' => 'Music',
        'price' => 12.9,
    ]
);
```


#### Deleting an entity

Deleting an entity by ID

```php
$this->repository->delete(3);
```

Mass-deleting entities by where closure

```php
$this->repository->deleteWhere('value', '<', 100);
```


## Paginating

Laravels paginating can be applied on queries, even combinated with other queries.

```php
// simple pagination
$this->repository
    ->paginate($per_page = 15);
// pagination on ordered result
$this->repository
    ->orderBy('name')
    ->paginate($per_page = 15);
```


## Getters: custom filtering and sorting

Entities can be easily filtered or sorted using getter classes. Filter keys and sorter methods in the request without matching function in the getter class will be ignored.

1. Create getter class to your existing repository: `php artisan make:getter 'YourModel'`.

2. Add the `Maarsson\Repository\Traits\GetterableTrait` trait to the model repository:
    ```php
    namespace App\Repositories;

    use Maarsson\Repository\Traits\GetterableTrait;

    class YourModelRepository extends AbstractEloquentRepository implements CustomerRepositoryInterface
    {
        use GetterableTrait;
    }
    ```

3. Add the required filtering method(s) to the created `YourModelGetter ` class. Filter method names must be camel cased and must end with `Filter`:
    ```php
    protected function nameFilter(string|null $searchString): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('name', 'LIKE', '%' . $searchString . '%');
    }
    ```

4. Get the filtered collection using the `filter[]` parameter in the query
    ```php
    // HTTP GET //localhost/yourmodel?filter[name]=foo
    public function index(\App\Filters\YourModelGetter $getter)
    {
        return $this->repository
            ->filter($getter)
            ->get();
    }
    ```

5. Add the required sorting method(s) to the created `YourModelGetter ` class. Sorter method names must be camel cased and must end with `Sorter`:
    ```php
    protected function relatedModelDateSorter(): \Illuminate\Database\Eloquent\Builder
    {
        return RelatedModel::select('date')
            ->whereColumn('this_model_column', 'related_table.column');
    }
    ```

6. Get the sorted collection using the `sort_by` parameter in the query
    ```php
    // HTTP GET //localhost/yourmodel?sort_by=related_model_date
    public function index(\App\Filters\YourModelGetter $getter)
    {
        return $this->repository
            ->order($getter)
            ->get();
    }
    ```

#### Simplified filtering, sorting and paginating

Get the filtered, sorted and paginated result by the helper methods.

```php
// HTTP GET //localhost/yourmodel?filter[name]=foo&page=5&per_page=20&sort_by=related_model_date&sort_order=desc
public function index(\App\Filters\YourModelGetter $getter)
{
    return $this->repository
        ->filter($getter)
        ->order()
        ->paginate();
}
```

The following request parameters are considered:
- `filter[]` default: `null`
- `page` default: `1`
- `per_page` default: `20`
- `sort_by`default: `'id'`
- `sort_order`default: `'asc'`


## Attribute (and relation) filter

Using the attribute filter trait you can control the model attributes to be fetched, including the relations (and its attributes).

1. Add the `Maarsson\Repository\Traits\EloquentAttributeFilterTrait` trait to the Eloquent model:
    ```php
    namespace App\Models;

    use Maarsson\Repository\Traits\EloquentAttributeFilterTrait;

    class YourModel extends Model
    {
        use EloquentAttributeFilterTrait;
    }
    ```

2. Get full control of the appended attributes and relations using a simple dot-notated array passed to the `withAttributes()` method. In this example you can also see how to combine this with the pagination.
    ```php
    // HTTP GET //localhost/yourmodel?filter[name]=foo&page=5&per_page=20&sort_by=related_model_date&sort_order=desc
    public function index(\App\Filters\YourModelGetter $getter)
    {
        return $this->repository
            ->filter($getter)
            ->order()
            ->paginate();
            ->through(
                fn ($item) => $item->withAttributes([
                    'id',
                    'name', // a model property
                    'finalPrice', // even a model accessor
                    'users' // a relation (with all of its attributes)
                    'users.posts:id,title', // a relations relation (with limited attributes)
                ])
            );
    }
    ```


## Using Eloquent Builder methods

Certain builder methods are available directly. Note using of the getter method at the end of the query.

**Sophisticated where queries:**
```php
$this->repository
    ->where('value', '<', 100)
    ->get();
$this->repository
    ->where('value', '>', 50)
    ->orWhere('value', '<', 100)
    ->get();
```

**Ordering result:**
```php
$this->repository->orderBy('title', 'desc')->get();
```

**Working with soft-deleted entities:**
```php
$this->repository->withTrashed()->get();
$this->repository->onlyTrashed()->get();
```

**Working with relations:**
```php
$this->repository->with('authors')->get();
```

**Reaching native Eloquent builder:**
```php
$this->repository->builder()
    ->whereIn('id', [1,2,3]);
    ->limit(5);
    ->offset(10);
    ->toSql();
```


## Events

Events fired automatically in certain cases:

### Create events

- Before an entity is beeing created, the `\App\Events\YourModelIsCreatingEvent::class` is fired:
    - where the `$event->attributes` property contains the creating data array.
- After an entity was created, the `\App\Events\YourModelWasCreatedEvent::class` is fired:
    - where the `$event->model` property contains the created entity,
    - and the `$event->attributes` property contains the creating data array.

### Update events

- Before an entity is beeing updated, the `\App\Events\YourModelIsUpdatingEvent::class` is fired:
    - where the `$event->model` property contains the original entity,
    - and the `$event->attributes` property contains the updating data array.
- After an entity was updated, the `\App\Events\YourModelWasUpdatedEvent::class` is fired:
    - where the `$event->model` property contains the updated entity,
    - and the `$event->attributes` property contains the updating data array.

### Delete events

- Before an entity is beeing deleted, the `\App\Events\YourModelIsDeletingEvent::class` is fired:
    - where the `$event->model` property contains the original entity.
- After an entity was deleted, the `\App\Events\YourModelWasDeletedEvent::class` is fired:
    - where the `$event->model` property contains the already deleted entity.


## Listeners

Event listeners automatically sets up for the events with the same naming conventions:
- `YourModelIsCreatingListener` listens the `YourModelIsCreatingEvent`
- `YourModelWasCreatedListener` listens the `YourModelWasCreatedEvent`

...and so on.

If a listeners `handle()` method of a before-event returns `false` it will prohibit the given action.


## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).
