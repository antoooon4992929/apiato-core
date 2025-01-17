<?php

namespace Apiato\Core\Criterias;

use Apiato\Core\Abstracts\Criterias\Criteria;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\RepositoryInterface as PrettusRepositoryInterface;

class KeywordsSearchCriteria extends Criteria
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($model, PrettusRepositoryInterface $repository)
    {
        $keywords = $this->request->get('keywords');
        $fields = $this->request->get('keywordsFields');
        if ($fields) {
            $fields = explode(';', $fields);
        } else {
            $fields = [];
        }
        $fields = $repository->intersectSearchFields($fields);

        if (empty($keywords) || count($fields) === 0) {
            return $model;
        }
        $keywords = trim($keywords);

        return $model->where(function ($query) use ($keywords, $fields, $repository) {
            $or = false;
            foreach ($fields as $field) {
                if (! $repository->setKeywordsSearchBuilder($query, $field, $keywords, $or)) {
                    $query->likeOrWhere($field, $keywords, $or);
                }
                $or = true;
            }
        });
    }
}
