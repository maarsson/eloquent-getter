# Eloqent getter package

This package adds and extendable Eloquent model getter/filter/sorter pattern to your Laravel project.

## Installation

1. Add package to your Laravel project: `composer require maarsson/eloquent-getter`
2. Publish config file `php artisan vendor:publish --tag=eloquent-getter-config`

## Usage

Models can be easily filtered or sorted using the package. Filter keys and sorter methods in the request without matching function in the getter class will be ignored.

1. Create getter class to your existing model: `php artisan make:getter 'YourModel'`.

2. Add the `Maarsson\EloquentGetter\Traits\GetterableTrait` trait to the model:
    ```php
    namespace App\Models;

    use Maarsson\EloquentGetter\Traits\GetterableTrait;

    class YourModel
    {
        use GetterableTrait;
    }
    ```

3. Add the required filtering method(s) to the created `YourModelGetter` class. Filter method names must be camel cased and must end with `Filter`:
    ```php
    protected function nameFilter(string|null $searchString): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('name', 'LIKE', '%' . $searchString . '%');
    }
    ```

4. Get the filtered collection using the `filter[]` parameter in the query
    ```php
    // HTTP GET //localhost/yourmodel?filter[name]=foo
    public function index(\App\Getters\YourModelGetter $getter)
    {
        return $model
            ->filter($getter)
            ->get();
    }
    ```

5. Add the required sorting method(s) to the created `YourModelGetter` class. Sorter method names must be camel cased and must end with `Sorter`:
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
        return $model
            ->order($getter)
            ->get();
    }
    ```

### Combined filtering, sorting and paginating

Get the filtered, sorted and paginated result by the helper methods.

```php
// HTTP GET //localhost/yourmodel?filter[name]=foo&page=5&per_page=20&sort_by=related_model_date&sort_order=desc
public function index(\App\Filters\YourModelGetter $getter)
{
    return $model
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


### Loading attributes and relations

You can even control the model attributes to be fetched, including the relations (and its attributes). Just use simple dot-notated array passed to the `withAttributes()` method. In this example you can also see how to combine this with the pagination.

```php
    // HTTP GET //localhost/yourmodel?filter[name]=foo&page=5&per_page=20&sort_by=related_model_date&sort_order=desc
    public function index(\App\Filters\YourModelGetter $getter)
    {
        return $model
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


## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).
