<?php

namespace Apiato\Core\Rules;

use Apiato\Core\Abstracts\Rules\Rule;
use Closure;

class AvailableSortsRule extends Rule
{
    public function __construct(private readonly array $availableSorts) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) && strlen($value) > 0) {
            $requestSorts = explode(';', $value);
            foreach ($requestSorts as $requestSort) {
                $isIdValueOrder = preg_match('/idValue\|(.+)/', $requestSort);
                if ($isIdValueOrder) {
                    $requestSort = 'id';
                }
                if (! array_key_exists($requestSort, $this->availableSorts)) {
                    $fail(__('Wrong order field'));
                }
            }
        }
    }
}
