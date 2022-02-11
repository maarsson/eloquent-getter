<?php

namespace Maarsson\Repository\Traits;

use Illuminate\Database\Eloquent\Builder;
use Maarsson\Repository\Filters\EloquentFilter;

trait Filterable
{
    /**
     * Scope a query to apply given filter.
     *
     * @param \Maarsson\Repository\Filters\EloquentFilter $filter
     *
     * @return \Maarsson\Repository\Filters\EloquentFilter
     */
    public function filter(EloquentFilter $filter): EloquentFilter
    {
        return $filter->apply($this->model()->query());
    }
}
