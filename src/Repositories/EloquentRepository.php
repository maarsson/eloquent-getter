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
    protected Model $model;

    /**
     * Creates a new instance.
     *
     * @param Illuminate\Database\Eloquent\Model $model
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
        return new $this->model;
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
     * Gets all entity.
     * Returned columns can be filtered.
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
     * Deletes an entity by its ID.
     *
     * @param string $id
     *
     * @return bool
     */
    public function delete(int|string $id) : bool
    {
        $model = $this->model()->find($id);

        event(new $this->events['IsDeleting']($model));

        $result = $model->delete();

        event(new $this->events['WasDeleted']($model));

        return $result;
    }

    /**
     * Finds an entity by its ID.
     * Returned columns can be filtered.
     *
     * @param int|string $id
     * @param string... $columns
     *
     * @return Illuminate\Database\Eloquent\Model
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
     * @return Illuminate\Database\Eloquent\Collection
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

        event(new $this->events['IsUpdating']($model, $attributes));

        $result = $model->update($attributes);

        event(new $this->events['WasUpdated']($model, $attributes));

        return $result;
    }
}
