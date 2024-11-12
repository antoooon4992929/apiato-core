<?php

namespace Apiato\Core\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait KeywordsSearchScopes
{
    public function scopeLikeOrWhere(Builder $query, $field, $keywords, bool $or = false): Builder
    {
        $databaseDriver = config('database.default') == 'pgsql';

        $mask = '%%%s%%';
        $keywords = sprintf($mask, $keywords);
        if ($databaseDriver == 'pgsql') {
            if ($or) {
                $query->orWhereRaw("$field ILIKE ?", [$keywords]);
            } else {
                $query->whereRaw("$field ILIKE ?", [$keywords]);
            }
        } else {
            if ($or) {
                $query->orWhereRaw("LOWER(`$field`) LIKE LOWER(?)", [$keywords]);
            } else {
                $query->whereRaw("LOWER(`$field`) LIKE LOWER(?)", [$keywords]);
            }
        }

        return $query;
    }

    public function scopeLikeOrWhereRelation(Builder $query, $relation, $field, $keywords, bool $or = false): Builder
    {
        if (is_string($relation)) {
            return $query->whereHas($relation, function (Builder $query) use ($field, $keywords, $or) {
                $query->likeOrWhere($field, $keywords, $or);
            });
        } elseif (is_array($relation) && count($relation) > 0) {
            $nextRelation = array_shift($relation);
            if (count($relation) === 0) {
                return $query->likeOrWhereRelation($nextRelation, $field, $keywords, $or);
            } else {
                return $query->whereHas($nextRelation, function (Builder $query) use ($relation, $field, $keywords, $or) {
                    $query->likeOrWhereRelation($relation, $field, $keywords, $or);
                });
            }
        }

        return $query;
    }

    public function scopeEqualOrWhere(Builder $query, $field, $keywords, bool $or = false): Builder
    {
        if ($or) {
            $query->orWhere($field, $keywords);
        } else {
            $query->where($field, $keywords);
        }

        return $query;
    }

    public function scopeJsonLikeOrWhere(Builder $query, $field, $prop, $keywords, bool $or = false): Builder
    {
        $mask = '%%%s%%';
        $keywords = sprintf($mask, $keywords);
        $databaseDriver = config('database.default');
        if ($databaseDriver == 'pgsql') {
            if ($or) {
                $query->orWhereRaw(
                    "$field ->> '$prop' ILIKE ?",
                    [$keywords]
                );
            } else {
                $query->whereRaw(
                    "$field ->> '$prop' ILIKE ?",
                    [$keywords]
                );
            }
        } else {
            if ($or) {
                $query->orWhereRaw(
                    "LOWER(json_extract(`$field`, '$.\"$prop\"')) LIKE LOWER(?)",
                    [$keywords]
                );
            } else {
                $query->whereRaw(
                    "LOWER(json_extract(`$field`, '$.\"$prop\"')) LIKE LOWER(?)",
                    [$keywords]
                );
            }
        }

        return $query;
    }

    public function scopeScopeOrWhere(
        Builder $query,
        $scopeName,
        $keywords,
        bool $or = false
    ): Builder {
        if ($or) {
            $query->orWhere->{$scopeName}($keywords);
        } else {
            $query->{$scopeName}($keywords);
        }

        return $query;
    }
}
