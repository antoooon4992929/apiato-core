<?php

namespace Apiato\Core\Traits;

trait CanGetFillableStatically
{
    public static function getFillableKeys()
    {
        return with(new static)->getFillable();
    }
}
