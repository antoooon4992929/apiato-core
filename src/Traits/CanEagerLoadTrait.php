<?php

namespace Apiato\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use League\Fractal\Manager;

trait CanEagerLoadTrait
{
    protected function eagerLoadRequestedRelations(): void
    {

        $this->scopeQuery(function (Builder|Model $model) {
            if (Request::has('include')) {
                $validIncludes = [];
                $requestedIncludes = app(Manager::class)
                    ->parseIncludes(Request::get('include', []))
                    ->getRequestedIncludes();
                foreach ($requestedIncludes as $includeName) {
                    $relationParts = explode('.', $includeName);
                    $camelCasedIncludeName = $this->validateNestedRelations($this->model, $relationParts);
                    if ($camelCasedIncludeName) {
                        $validIncludes[] = $camelCasedIncludeName;
                    }
                }

                return $model->with($validIncludes);
            }

            return $model;
        });
    }

    private function validateNestedRelations(Builder|Model $model, array $relationParts): ?string
    {
        if (empty($relationParts)) {
            return null;
        }

        $relation = array_shift($relationParts);

        if (! method_exists($model, $relation)) {
            return null;
        }

        $nextModel = $model->$relation()->getRelated();

        if (empty($relationParts)) {
            return $relation;
        }

        $nextRelation = $this->validateNestedRelations($nextModel, $relationParts);

        if (is_null($nextRelation)) {
            return null;
        }

        return $relation.'.'.$nextRelation;
    }
}
