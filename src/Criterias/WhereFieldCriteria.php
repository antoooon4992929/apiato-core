<?php

namespace Apiato\Core\Criterias;

use App\Ship\Parents\Criterias\Criteria;
use Prettus\Repository\Contracts\RepositoryInterface as PrettusRepositoryInterface;

class WhereFieldCriteria extends Criteria
{
    public function __construct(
        private string $field,
        private string $operator,
        private string $value,
    ) {}

    public function apply($model, PrettusRepositoryInterface $repository)
    {
        return $model->where($this->field, $this->operator, $this->value);
    }
}
