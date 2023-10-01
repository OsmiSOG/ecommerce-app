<?php

namespace App\Invokables;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FilterMultipleFields implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        if (isset($value)) {
            $fields = explode(',', $property);

            $query->where(function ($query) use ($fields, $value) {
                foreach ($fields as $field) {
                    $query = $query->orWhere($field, 'like', '%'.$value.'%');
                }
            });
        }
        return $query;
    }
}
