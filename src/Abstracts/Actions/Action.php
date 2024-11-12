<?php

namespace Apiato\Core\Abstracts\Actions;

use Illuminate\Support\Facades\DB;
use Throwable;

abstract class Action
{
    /**
     * @throws Throwable
     */
    public function transactionalRun(...$arguments)
    {
        return DB::transaction(function () use ($arguments) {
            return static::run(...$arguments);
        });
    }
}
