<?php

namespace Apiato\Core\Traits;

use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

trait HasRequestCriteriaTrait
{
    /**
     * @throws RepositoryException
     */
    public function addRequestCriteria(): static
    {
        $this->pushCriteria(app(RequestCriteria::class));
        $this->prepareOrderByValues();

        return $this;
    }

    public function removeRequestCriteria(): static
    {
        $this->popCriteria(RequestCriteria::class);

        return $this;
    }

    private function prepareOrderByValues(): void
    {
        $query = request()->query();
        $key = config('repository.criteria.params.orderBy', 'orderBy');
        if (! array_key_exists($key, $query)) {
            return;
        }
        $orderByQueryString = $query[$key];
        $orderByArray = explode(';', $orderByQueryString);
        $preparedOrderByArray = [];
        $availableSorts = $this->getAvailableSorts();

        foreach ($orderByArray as $orderBy) {
            $isIdValueOrder = preg_match('/idValue\|(.+)/', $orderBy);
            if ($isIdValueOrder && array_key_exists('id', $availableSorts)) {
                $preparedOrderByArray[] = $orderBy;
            } else {
                if (array_key_exists($orderBy, $availableSorts)) {
                    $preparedOrderByArray[] = $availableSorts[$orderBy];
                }
            }
        }
        $query[$key] = implode(';', $preparedOrderByArray);

        request()->query->replace($query);
    }
}
