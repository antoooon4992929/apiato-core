<?php

namespace Apiato\Core\Abstracts\Models;

use Apiato\Core\Scopes\KeywordsSearchScopes;
use Apiato\Core\Traits\CanGetFillableStatically;
use Apiato\Core\Traits\CanGetTableNameStatically;
use Apiato\Core\Traits\FactoryLocatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as LaravelEloquentModel;

abstract class Model extends LaravelEloquentModel
{
    use CanGetFillableStatically;
    use CanGetTableNameStatically;
    use FactoryLocatorTrait, HasFactory {
        FactoryLocatorTrait::newFactory insteadof HasFactory;
    }
    use KeywordsSearchScopes;
}
