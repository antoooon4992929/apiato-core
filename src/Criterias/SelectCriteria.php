<?php

namespace Apiato\Core\Criterias;

use Apiato\Core\Abstracts\Criterias\Criteria;
use Prettus\Repository\Contracts\RepositoryInterface as PrettusRepositoryInterface;

class SelectCriteria extends Criteria
{
    public function __construct(
        private readonly array $selects,
    ) {}

    public function apply($model, PrettusRepositoryInterface $repository)
    {
        return $model->select($this->selects);
    }
}
