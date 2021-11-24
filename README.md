# Laravel model repository pattern

This package adds and extendable repository pattern to your Laravel project.

## Installation

1. Add package to your Laravel project: `composer require maarsson/laravel-repository`
2. Publish config file `php aritsan vendor:publish --tag=repository-config`
3. Create required folders: `mkdir app/Interfaces && mkdir app/Repositories`

## Usage

1. Create repository to your existing Eloquent model: `php artisan make:repository 'YourModel'`
2. Add your model name to the `config/repository.php`:
    ```php
        'models' => [
            'YourModel'
        ],
    ```
3. Use dependency injection in your controller:
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


## Methods

#### Retrieving entities

Retrieving all entities from the database
```php
$collection = $this->repository->all();
```

Retrieving entity by ID
```php
$collection = $this->repository->find(3);
```

Retrieving entity by a specific column
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


## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).
