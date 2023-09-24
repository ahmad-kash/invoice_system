<?php

namespace Tests\Traits;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;


trait HasCollectionAssert
{

    public function assertEloquentCollectionsEqual(Collection|EloquentCollection $collection1, Collection|EloquentCollection $collection2, Collection|array $keys = []): self
    {
        $this->assertTrue($this->eloquentCollectionsAreEqual(...func_get_args()), "Failed to assert that collection1 is not equal to collection2");
        return $this;
    }

    public function eloquentCollectionsAreEqual(Collection|EloquentCollection $collection1, Collection|EloquentCollection $collection2, Collection|array $keys = []): bool
    {
        $collection1 = $this->normalizeCollection($collection1, $keys);
        $collection2 = $this->normalizeCollection($collection2, $keys);

        return $collection1->diffAssoc($collection2)->isEmpty() && $collection2->diffAssoc($collection1)->isEmpty();
    }

    private function normalizeCollection(Collection|EloquentCollection $collection, Collection|array $keys = []): Collection
    {
        $collection = $collection->map(fn ($item) => collect($item->attributesToArray())->sortKeys());
        if (collect($keys)->isEmpty())
            return $collection;

        return $collection->map(fn ($item) => $item->only($keys));
    }
}
