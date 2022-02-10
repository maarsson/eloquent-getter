# Laravel model repository pattern

This package adds and extendable repository pattern to your Laravel project.


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
    use App\Contracts\YourModelRepositoryContract;

    class TestController extends Controller
    {
        private $repository;

        public function __construct(YourModelRepositoryContract $repository)
        {
            $this->repository = $repository;
        }
    }
    ```

3. Optionally you can add custom methods to the repository:
    ```php
        class YourModelRepository extends EloquentRepository implements YourModelRepositoryContract
        {
            public function doSomeConverting()
            {
                // your code here
            }
        }
    ```
    and also to the interface class:
    ```php
        interface YourModelRepositoryContract
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

You can also specify which columns to be fetched
```php
$collection = $this->repository->all('id', 'title');
$collection = $this->repository->find(3, 'id', 'title');
$collection = $this->repository->findBy('title', 'Music', 'id', 'title');
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
$entity = $this->repository->delete(3);
```


## Filtering

Entities can be easily filtered using custom filter classes. Filter keys in the request without matching function in the filter class will be ignored.

1. Create filter class to your existing repository: `php artisan make:filter 'YourModel'`.

2. Add the required filtering method(s) to the created `YourModelFilter ` class:
    ```php
    protected function name(string $searchString): Builder
    {
        return $this->builder->where('name', 'LIKE', '%' . $searchString . '%');
    }
    ```

3. Add the `Maarsson\Repository\Traits\Filterable` trait to the model repository:
    ```php
    namespace App\Repositories;

    use Maarsson\Repository\Traits\Filterable;

    class YourModelRepository extends EloquentRepository implements CustomerRepositoryContract
    {
        use Filterable;
    }
    ```

4. Get the filtered collection using the `filter[]` parameter in the query
    ```php
        // HTTP GET //localhost/yourmodel?filter[name]=foo
        public function index(\Illuminate\Http\Request $request)
        {
            $this->repository
                ->filter(new YourModelFilter($request))
                ->get();
        }
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


## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).
