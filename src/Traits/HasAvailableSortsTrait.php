<?php

namespace Apiato\Core\Traits;

trait HasAvailableSortsTrait
{
    protected array $availableSorts = [];

    public function getAvailableSorts(): array
    {
        $sorts = [];
        foreach ($this->availableSorts as $key => $value) {
            if (is_string($key)) {
                $sorts[$key] = $value;
            } else {
                $sorts[$value] = $value;
            }
        }

        return $sorts;
    }
}
