<?php

namespace Maarsson\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * This interface describes an eloquent repository interface.
 */
interface EloquentRepositoryInterface
{
    /**
     * Creates a new model instance.
     *
     * @return Model
     */
    public function model(): Model;

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder(): Builder;

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function withTrashed(): Builder;

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function onlyTrashed(): Builder;

    /**
     * Return with column filter array.
     *
     * @param array $columns The columns
     *
     * @return array
     */
    public function columns(array $columns = []): array;

    /**
     * Set the relationships that should be eager loaded.
     *
     * @param array|string $relations
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function with($relations): Builder;

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
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator;

    /**
     * Gets all entity.
     * Returned columns can be filtered.
     *
     * @param string... $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(string ...$columns): Collection;

    /**
     * Gets the number of all entity.
     *
     * @return int
     */
    public function count(): int;

    /**
     * Gets the first entity by the timestamp.
     * Returned columns can be filtered.
     *
     * @param string... $columns
     *
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function first(string ...$columns): ?Model;

    /**
     * Gets the last entity by the timestamp.
     * Returned columns can be filtered.
     *
     * @param string... $columns
     *
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function last(string ...$columns): ?Model;

    /**
     * Creates a new entity.
     *
     * @param array $attributes
     *
     * @return false|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes): false|Model;

    /**
     * Deletes an entity by its ID.
     *
     * @param int|string $id
     *
     * @return bool
     */
    public function delete(int|string $id): bool;

    /**
     * Deletes entities by a where query result.
     *
     * @param array|Closure|\Illuminate\Database\Query\Expression|string $column
     * @param mixed $operator
     * @param mixed $value
     *
     * @return bool
     */
    public function deleteWhere($column, $operator = null, $value = null): bool;

    /**
     * Finds an entity by its ID.
     * Returned columns can be filtered.
     *
     * @param int|string $id
     * @param string... $columns
     *
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function find(int|string $id, string ...$columns): ?Model;

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
    public function findBy(string $field, $value = null, string ...$columns): ?Collection;

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
    public function where($column, $operator = null, $value = null, $boolean = 'and'): Builder;

    /**
     * Add an "or where" clause to the query.
     *
     * @param array|Closure|\Illuminate\Database\Query\Expression|string $column
     * @param mixed $operator
     * @param mixed $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function orWhere($column, $operator = null, $value = null): Builder;

    /**
     * Adds an "order by" clause to the query.
     *
     * @param Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Query\Expression|string $column
     * @param string $direction
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function orderBy($column, $direction = 'asc'): Builder;

    /**
     * Updates an entity by its ID.
     *
     * @param int|string $id
     * @param array $attributes
     *
     * @return bool
     */
    public function update(int|string $id, array $attributes): bool;
}
