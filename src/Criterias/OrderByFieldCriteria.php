<?php

namespace Apiato\Core\Criterias;

use Apiato\Core\Abstracts\Criterias\Criteria;
use Prettus\Repository\Contracts\RepositoryInterface as PrettusRepositoryInterface;

class OrderByFieldCriteria extends Criteria
{
    public function __construct(
        private string $field,
        private string $sortOrder = 'asc'
    ) {}

    public function apply($model, PrettusRepositoryInterface $repository)
    {
        return $model->orderBy($this->field, $this->sortOrder);
    }
}
