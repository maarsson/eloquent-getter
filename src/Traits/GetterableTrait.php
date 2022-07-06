<?php

namespace Maarsson\Repository\Traits;

use Maarsson\Repository\Getters\AbstractEloquentGetter;

trait GetterableTrait
{
    /**
     * Scope a query to apply given filter.
     *
     * @param \Maarsson\Repository\Filters\AbstractEloquentGetter $filter
     *
     * @return \Maarsson\Repository\Filters\AbstractEloquentGetter
     */
    public function filter(?AbstractEloquentGetter $getter = null): AbstractEloquentGetter
    {
        return $getter->apply($this->model()->query())->filter();
    }

    /**
     * Scope a query to apply given sorter.
     *
     * @param \Maarsson\Repository\Filters\AbstractEloquentGetter $filter
     *
     * @return \Maarsson\Repository\Filters\AbstractEloquentGetter
     */
    public function order(?AbstractEloquentGetter $getter = null): AbstractEloquentGetter
    {
        return $getter->apply($this->model()->query())->order();
    }
}
