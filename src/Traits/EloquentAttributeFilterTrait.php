<?php

namespace Maarsson\Repository\Traits;

use Illuminate\Support\Str;

trait EloquentAttributeFilterTrait
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
            $this->with($this->getValidRelations())
                ->find($this->id)
                ->only($this->getValidAttributes());
    }

    private function getValidRelations()
    {
        return collect($this->withAttributes)->filter(function ($attribute) {
            $withoutColumnSpecifications = explode(':', $attribute)[0];

            return $this->isRelation($withoutColumnSpecifications) || Str::contains($withoutColumnSpecifications, '.');
        })->toArray();
    }

    private function getValidAttributes()
    {
        return collect($this->withAttributes)->map(function ($attribute) {
            return explode('.', explode(':', $attribute)[0])[0];
        })->toArray();
    }
}
