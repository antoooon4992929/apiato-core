<?php

namespace Apiato\Core\Criterias;

use Apiato\Core\Abstracts\Criterias\Criteria;

class ThisBetweenCriteria extends Criteria
{
    public function __construct(
        private string $field,
        private array $between,
    ) {}

    public function apply($model, $repository)
    {
        return $model->whereBetween($this->field, $this->between);
    }
}
