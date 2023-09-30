<?php

namespace Maarsson\EloquentGetter\Traits;

use Illuminate\Support\Str;
use Maarsson\EloquentGetter\Getters\AbstractEloquentGetter;

trait GetterableTrait
{
    /**
     * The required model attributes and/or relations´ attributes,
     * even with column specifications.
     * Note that the subrelations´ id must be given when querying subrelation.
     *
     * @var array<string>
     */
    private array $withAttributes = [];

    /**
     * Get a subset of the model's attributes and/or relations' attributes.
     *
     * @param array $attributes
     *
     * @return array
     */
    public function withAttributes(?array $attributes = []): array
    {
        $this->withAttributes = $attributes;

        return empty($this->withAttributes) ?
            $this->toArray()
            :
            $this->load($this->getValidRelations())
                ->only($this->getValidAttributes());
    }

    /**
     * Scope a query to apply given filter.
     *
     * @param \Maarsson\EloquentGetter\Filters\AbstractEloquentGetter $filter
     *
     * @return \Maarsson\EloquentGetter\Filters\AbstractEloquentGetter
     */
    public function filter(?AbstractEloquentGetter $getter = null): AbstractEloquentGetter
    {
        return $getter->apply($this->model()->query())->filter();
    }

    /**
     * Scope a query to apply given sorter.
     *
     * @param \Maarsson\EloquentGetter\Filters\AbstractEloquentGetter $filter
     *
     * @return \Maarsson\EloquentGetter\Filters\AbstractEloquentGetter
     */
    public function order(?AbstractEloquentGetter $getter = null): AbstractEloquentGetter
    {
        return $getter->apply($this->model()->query())->order();
    }

    /**
     * Filters the required attributes
     * to only the valid relations.
     *
     * @return array
     */
    private function getValidRelations(): array
    {
        return collect($this->withAttributes)->filter(function ($attribute) {
            $withoutColumnSpecifications = explode(':', $attribute)[0];

            return $this->isRelation($withoutColumnSpecifications) || Str::contains($withoutColumnSpecifications, '.');
        })->toArray();
    }

    /**
     * Filters the required attributes
     * to only the valid attributes.
     *
     * @return array
     */
    private function getValidAttributes(): array
    {
        return collect($this->withAttributes)->map(function ($attribute) {
            return explode('.', explode(':', $attribute)[0])[0];
        })->toArray();
    }
}
