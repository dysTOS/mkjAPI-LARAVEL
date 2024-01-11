<?php

namespace App\DAO;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ListQueryDAO
{
    private $model;
    private $options;

    function __construct($model, $options = null)
    {
        $this->model = $model;
        $this->options = $options;
    }

    public function getListOutput(Request $request)
    {
        $query = $this->model::query();
        $this->setFilters($query, $request);
        $totalCount = $query->count();

        if ($request['sort'] != null) {
            $query->orderBy($request['sort']['field'], $request['sort']['order'] ?? 'asc');
        }

        $query->skip($request['skip'] ?? 0)
            ->take($request['take'] ?? PHP_INT_MAX);

        $values = $query->get();
        if ($this->options != null && $this->options['load'] != null) {
            $values->load($this->options['load']);
        }

        return array(
            "totalCount" => $totalCount,
            "values" => $values
        );
    }

    private function setFilters(Builder $builder, Request $request): Builder
    {
        if ($request['filterAnd'] != null) {
            foreach ($request['filterAnd'] as $filter) {
                $builder->where($filter['field'], $filter['operator'] ?? 'LIKE', $filter['value']);
            }
        }
        if ($request['filterOr'] != null) {
            $builder->where(
                function ($builder) use ($request) {
                    foreach ($request['filterOr'] as $filter) {
                        $builder->orWhere($filter['field'], $filter['operator'] ?? 'LIKE', $filter['value']);
                    }
                    return $builder;
                }
            );
        }
        if ($request['globalFilter'] != null) {
            $builder->where(
                function ($builder) use ($request) {
                    foreach ($request['globalFilter']['fields'] as $field) {
                        $builder->orWhere($field, 'like', '%' . $request['globalFilter']['value'] . '%');
                    }
                    return $builder;
                }
            );
        }
        return $builder;
    }
}
