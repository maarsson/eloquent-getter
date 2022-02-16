<?php

namespace Maarsson\Repository\Getters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class EloquentGetter
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
     * Class constructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Constructs the builder on the getter class.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return self
     */
    public function apply(Builder $builder): self
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * Applies all the requested filters if available.
     *
     * @return self
     */
    public function filter(): self
    {
        $this->getFilters()->each(function ($value, $method) {
            $this->$method($value);
        });

        return $this;
    }

    /**
     * Adds ordering to query.
     *
     * @return self
     */
    public function order(): self
    {
        $sortBy = $this->getSorterMethod() ?: $this->getSorterColumn();

        if ($sortBy) {
            $this->builder->orderBy(
                $this->getSorterMethod() ?: $this->request->get('sort_by', 'id'),
                $this->request->get('sort_order', 'asc')
            );
        }

        return $this;
    }

    /**
     * Gets query result.
     *
     * @return self
     */
    public function get()
    {
        return $this->builder->get();
    }

    /**
     * Adds pagination to query and gets the result
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
     * Keys also converts to camel case.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getFilters(): Collection
    {
        return collect($this->request->get('filter'))
            ->mapWithKeys(fn ($value, $key) => [
                Str::of($key)->camel()->append('Filter')->toString() => $value,
            ])
            ->only($this->getFilterMethods());
    }

    /**
     * Gets all the available filter methods.
     *
     * @return array
     */
    protected function getFilterMethods(): array
    {
        return array_diff(
            get_class_methods(static::class), // methods of getter class
            get_class_methods(self::class) // methods of this package class
        );
    }

    /**
     * Gets the requested sorter method.
     * Request string converts to camel case.
     *
     * @return null|\Illuminate\Database\Eloquent\Builder
     */
    protected function getSorterMethod(): ?Builder
    {
        $method = Str::of($this->request->get('sort_by'))
            ->camel()
            ->append('Sorter')
            ->toString();

        return $this->hasSorterMethod($method) ? $this->$method() : null;
    }

    /**
     * Gets the requested sorter column.
     *
     * @return null|string
     */
    protected function getSorterColumn(): ?string
    {
        $column = $this->request->get('sort_by', 'id');

        return $this->hasColumn($column) ? $column : null;
    }

    /**
     * Determines if the model table has the column.
     *
     * @param string $column
     *
     * @return bool
     */
    protected function hasColumn(string $column): bool
    {
        return $this->builder->getConnection()
           ->getSchemaBuilder()
           ->hasColumn($this->builder->getModel()->getTable(), $column);
    }

    /**
     * Determines if the sorter class has the method.
     *
     * @param string $method
     *
     * @return bool
     */
    protected function hasSorterMethod(string $method): bool
    {
        return method_exists(static::class, $method);
    }
}
