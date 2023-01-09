<?php

namespace Maarsson\Repository\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Maarsson\Repository\Interfaces\EloquentRepositoryInterface;
use Maarsson\Repository\Traits\HasModelEventsTrait;
use Maarsson\Repository\Traits\UsesFolderConfigTrait;

abstract class AbstractEloquentRepository implements EloquentRepositoryInterface
{
    use HasModelEventsTrait, UsesFolderConfigTrait;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected Model $model;

    /**
     * Creates a new instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;

        $this->setEventsForModel($this->model);
    }

    /**
     * Creates a new model instance.
     *
     * @return Model
     */
    public function model(): Model
    {
        return new $this->model();
    }

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder(): Builder
    {
        return $this->model()->query();
    }

    /**
     * Inlcudes even soft deleted entities to query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function withTrashed(): Builder
    {
        return $this->model()->withTrashed();
    }

    /**
     * Inlcudes only soft deleted entities to query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function onlyTrashed(): Builder
    {
        return $this->model()->onlyTrashed();
    }

    /**
     * Return with column filter array.
     *
     * @param array $columns The columns
     *
     * @return array
     */
    public function columns(array $columns = []): array
    {
        return empty($columns) ? ['*'] : $columns;
    }

    /**
     * Set the relationships that should be eager loaded.
     *
     * @param array|string $relations
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function with($relations): Builder
    {
        return $this->builder()->with(is_string($relations) ? func_get_args() : $relations);
    }

    /**
     * Paginate the given query.
     *
     * @param null|int $perPage
     * @param array $columns
     * @param string $pageName
     * @param null|int $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator
    {
        return $this->builder()->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * Gets all entity.
     * Returned columns can be filtered.
     *
     * @param string... $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(string ...$columns): Collection
    {
        return $this->model()->all(
            $this->columns($columns)
        );
    }

    /**
     * Gets the number of all entity.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->builder()->count();
    }

    /**
     * Gets the first entity by the timestamp.
     * Returned columns can be filtered.
     *
     * @param string... $columns
     *
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function first(string ...$columns): ?Model
    {
        return $this->builder()
            ->orderBy('created_at', 'asc')
            ->first(
                $this->columns($columns)
            );
    }

    /**
     * Gets the last entity by the timestamp.
     * Returned columns can be filtered.
     *
     * @param string... $columns
     *
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function last(string ...$columns): ?Model
    {
        return $this->builder()
            ->orderBy('created_at', 'desc')
            ->first(
                $this->columns($columns)
            );
    }

    /**
     * Creates a new entity.
     *
     * @param array $attributes
     *
     * @return false|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes): false|Model
    {
        if (empty(event(new $this->events['IsCreating']($attributes)))) {
            return false;
        }

        $model = $this->model()->create($attributes);

        if ($model) {
            event(new $this->events['WasCreated']($model, $attributes));
        }

        return $model;
    }

    /**
     * Deletes an entity by its ID.
     *
     * @param string $id
     *
     * @return bool
     */
    public function delete(int|string $id): bool
    {
        $model = $this->model()->find($id);

        if ($model) {
            if (empty(event(new $this->events['IsDeleting']($model)))) {
                return false;
            }
        }

        $result = $model->delete();

        if ($model) {
            event(new $this->events['WasDeleted']($model));
        }

        return $result;
    }

    /**
     * Deletes entities by a where query result.
     *
     * @param array|Closure|\Illuminate\Database\Query\Expression|string $column
     * @param mixed $operator
     * @param mixed $value
     *
     * @return bool
     */
    public function deleteWhere($column, $operator = null, $value = null): bool
    {
        $result = true;

        $this->builder()
            ->where($column, $operator, $value)
            ->each(function ($entity) use ($result) {
                $result = $result && $this->delete($entity->id);
            });

        return $result;
    }

    /**
     * Finds an entity by its ID.
     * Returned columns can be filtered.
     *
     * @param int|string $id
     * @param string... $columns
     *
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function find(int|string $id, string ...$columns): ?Model
    {
        return $this->model()->find(
            $id,
            $this->columns($columns)
        );
    }

    /**
     * Finds an entity by custom field.
     * Returned columns can be filtered.
     *
     * @param string $field The search field
     * @param null|mixed $value The searched value
     * @param string... $columns
     *
     * @return null|\Illuminate\Database\Eloquent\Collection
     */
    public function findBy(string $field, $value = null, string ...$columns): ?Collection
    {
        return $this->model()->where(
            $field,
            $value
        )
            ->get(
                $this->columns($columns)
            );
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param array|Closure|\Illuminate\Database\Query\Expression|string $column
     * @param mixed $operator
     * @param mixed $value
     * @param string $boolean
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and'): Builder
    {
        return $this->builder()->where($column, $operator, $value, $boolean);
    }

    /**
     * Add an "or where" clause to the query.
     *
     * @param array|Closure|\Illuminate\Database\Query\Expression|string $column
     * @param mixed $operator
     * @param mixed $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function orWhere($column, $operator = null, $value = null): Builder
    {
        return $this->builder()->orWhere($column, $operator, $value);
    }

    /**
     * Adds an "order by" clause to the query.
     *
     * @param Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Query\Expression|string $column
     * @param string $direction
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function orderBy($column, $direction = 'asc'): Builder
    {
        return $this->builder()->orderBy($column, $direction);
    }

    /**
     * Updates an entity by its ID.
     *
     * @param int|string $id
     * @param array $attributes
     *
     * @return bool
     */
    public function update(int|string $id, array $attributes): bool
    {
        $model = $this->model()->find($id);

        if ($model) {
            if (empty(event(new $this->events['IsUpdating']($model, $attributes)))) {
                return false;
            }
        }

        $result = $model->update($attributes);

        if ($model) {
            event(new $this->events['WasUpdated']($model, $attributes));
        }

        return $result;
    }
}
