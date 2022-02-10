<?php

namespace Maarsson\Repository\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * This interface describes an eloquent repository interface.
 */
interface EloquentRepositoryContract
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
     * Return with column filter array.
     *
     * @param array $columns The columns
     *
     * @return array
     */
    public function columns(array $columns = []): array;

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
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all(string ...$columns): Collection;

    /**
     * Creates a new entity.
     *
     * @param array $attributes
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes): Model;

    /**
     * Deletes an entity by its ID.
     *
     * @param int|string $id
     *
     * @return bool
     */
    public function delete(int|string $id): bool;

    /**
     * Finds an entity by its ID.
     * Returned columns can be filtered.
     *
     * @param int|string $id
     * @param string... $columns
     *
     * @return Illuminate\Database\Eloquent\Model
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
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function findBy(string $field, $value = null, string ...$columns): ?Collection;

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
