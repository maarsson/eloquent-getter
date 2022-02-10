<?php

namespace Maarsson\Repository\Traits;

use Illuminate\Database\Eloquent\Builder;
use Maarsson\Repository\Filters\EloquentFilter;

trait Filterable
{
    /**
     * Scope a query to apply given filter.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Maarsson\Repository\Filters\EloquentFilter $filter
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filter(EloquentFilter $filter): Builder
    {
        return $filter->apply($this->model->query());
    }
}
