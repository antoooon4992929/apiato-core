<?php

namespace Apiato\Core\Traits;

trait CanGetTableNameStatically
{
    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
