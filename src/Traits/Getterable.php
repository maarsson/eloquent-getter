<?php

namespace Maarsson\Repository\Traits;

use Illuminate\Database\Eloquent\Builder;
use Maarsson\Repository\Getters\EloquentGetter;

trait Getterable
{
    /**
     * Scope a query to apply given filter.
     *
     * @param \Maarsson\Repository\Filters\EloquentGetter $filter
     *
     * @return \Maarsson\Repository\Filters\EloquentGetter
     */
    public function filter(EloquentGetter $getter = null): EloquentGetter
    {
        return $getter->apply($this->model()->query())->filter();
    }

    /**
     * Scope a query to apply given sorter.
     *
     * @param \Maarsson\Repository\Filters\EloquentGetter $filter
     *
     * @return \Maarsson\Repository\Filters\EloquentGetter
     */
    public function order(EloquentGetter $getter = null): EloquentGetter
    {
        return $getter->apply($this->model()->query())->order();
    }
}
