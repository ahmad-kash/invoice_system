<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class ExistsOrUnique implements ValidationRule
{

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $id = Str::of(request()->url())->afterLast('/')->value;
        // if Role exists with it's id and name this rule will pass
        // if Role exists with just the name then the role is not unique then this rule will fail
        if (!Role::where('id', $id)->where('name', $value)->exists() && Role::where('name', $value)->exists())
            $fail("role must be unique");
    }
}
