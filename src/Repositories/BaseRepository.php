<?php

namespace Maarsson\Repository\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Maarsson\Repository\Interfaces\EloquentRepositoryInterface;

abstract class BaseRepository implements EloquentRepositoryInterface
{
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
     * Gets all entity.
     * Returned columns can be filtered
     *
     * @param string... $columns
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function all(string ...$columns): Collection
    {
        return $this->model()->all(
            empty($columns) ? '*' : $columns
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
        return $this->model()->create($attributes);
    }

    /**
     * Finds an entity by its ID.
     *
     * @param mixed $id
     *
     * @return Illuminate\Database\Eloquent\Model|Illuminate\Support\Collection
     */
    public function find($id): ?Model
    {
        return $this->model()->find($id);
    }
}
