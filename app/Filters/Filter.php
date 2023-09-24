<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class Filter extends Builder
{

    protected array $allowedFilter = [];

    public function filter($filters)
    {
        foreach ($filters as $key => $value) {
            //check if there is a where filter
            if (in_array($key, $this->allowedFilter) && $value) {
                $this->$key($value);
            }
        }

        $this->flashFiltersToSession();

        return $this;
    }

    private function flashFiltersToSession()
    {
        request()->flashOnly($this->allowedFilter);
    }
}
