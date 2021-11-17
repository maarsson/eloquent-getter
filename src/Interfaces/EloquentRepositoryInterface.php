<?php

namespace Maarsson\Repository\Interfaces;

use Illuminate\Database\Eloquent\Model;

/**
 * This interface describes an eloquent repository interface.
 */
interface EloquentRepositoryInterface
{
    /**
     * Creates a new entity.
     *
     * @param array $attributes
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes): Model;

    /**
     * Finds an entity by its ID.
     *
     * @param mixed $id
     *
     * @return Illuminate\Database\Eloquent\Model|Illuminate\Support\Collection
     */
    public function find($id): ?Model;
}
