<?php

namespace Maarsson\Repository\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Maarsson\Repository\Contracts\EloquentRepositoryContract;
use Maarsson\Repository\Traits\HasModelEvents;
use Maarsson\Repository\Traits\UsesFolderConfig;

abstract class EloquentRepository implements EloquentRepositoryContract
{
    use HasModelEvents, UsesFolderConfig;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Creates a new instance
     *
     * @param Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->setEventsForModel($this->model);
    }

    /**
     * Creates a new model instance
     *
     * @return Model
     */
    public function model(): Model
    {
        return new $this->model;
    }

    /**
     * Return with column filter array
     *
     * @return array
     */
    public function columns(array $columns = []): array
    {
        return empty($columns) ? ['*'] : $columns;
    }

    /**
     * Gets all entity.
     * Returned columns can be filtered
     *
     * @param string... $columns
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all(string ...$columns): Collection
    {
        return $this->model()->all(
            $this->columns($columns)
        );
    }

    /**
     * Creates a new entity.
     *
     * @param array $attributes
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes): Model
    {
        event(new $this->events['IsCreating']($attributes));

        $model = $this->model()->create($attributes);

        event(new $this->events['WasCreated']($model, $attributes));

        return $model;
    }

    /**
     * Finds an entity by its ID.
     * Returned columns can be filtered
     *
     * @param mixed $id
     * @param string... $columns
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function find($id, string ...$columns): ?Model
    {
        return $this->model()->find(
            $id,
            $this->columns($columns)
        );
    }

    /**
     * Finds an entity by custom column.
     * Returned columns can be filtered
     *
     * @param mixed $id
     * @param string... $columns
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function findBy(string $column, $value = null, string ...$columns): ?Collection
    {
        return $this->model()->where(
            $column,
            $value
        )
        ->get(
            $this->columns($columns)
        );
    }
}
