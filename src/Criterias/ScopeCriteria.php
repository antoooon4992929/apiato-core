<?php

namespace Apiato\Core\Criterias;

use App\Ship\Parents\Criterias\Criteria;
use Prettus\Repository\Contracts\RepositoryInterface as PrettusRepositoryInterface;

class ScopeCriteria extends Criteria
{
    public function __construct(
        private string $scopeName,
        private array $scopeParams = [],
    ) {}

    public function apply($model, PrettusRepositoryInterface $repository)
    {
        return $model->{$this->scopeName}(...$this->scopeParams);
    }
}
