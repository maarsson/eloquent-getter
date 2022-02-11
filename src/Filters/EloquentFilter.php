<?php

namespace Maarsson\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class EloquentFilter
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected Request $request;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected Builder $builder;

    /**
     * Filter constructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply all the requested filters if available.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return self
     */
    public function apply(Builder $builder): self
    {
        $this->builder = $builder;

        $this->getFilters()->each(function($value, $method) {
            $this->$method($value);
        });

        return $this;
    }

    /**
     * Adds ordering to query
     *
     * @return self
     */
    public function get()
    {
        return $this->builder->get();
    }

    /**
     * Adds ordering to query
     *
     * @return self
     */
    public function order(): self
    {
        $this->builder->orderBy(
            $this->request->get('sort_by', 'name'),
            $this->request->get('sort_order', 'desc')
        );

        return $this;
    }

    /**
     * Adds pagination to query
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(): LengthAwarePaginator
    {
        return $this->builder->paginate(
            $this->request->get('per_page', 20)
        );
    }

    /**
     * Get all the filters that can be applied.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getFilters(): Collection
    {
        return collect($this->request->get('filter'))
                ->only($this->getFilterMethods());
    }

    /**
     * Get all the available filter methods.
     *
     * @return array
     */
    protected function getFilterMethods(): array
    {
        return array_diff(
            get_class_methods(static::class), // methods of filter class
            get_class_methods(self::class) // methods of this package class
        );
    }

}
