<?php

namespace Apiato\Core\Abstracts\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

abstract class Rule implements ValidationRule
{
    abstract public function validate(string $attribute, mixed $value, Closure $fail): void;
}
