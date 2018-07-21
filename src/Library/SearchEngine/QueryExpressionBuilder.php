<?php

namespace ReadShare\Library\SearchEngine;

use ReadShare\Library\SearchEngine\Analyzer\Analyzer;

class QueryExpressionBuilder {
    /**生成单个match
     * @param $key
     * @param $string
     * @param int $boost
     * @param string $analyzer
     * @return array
     */
    public function match($key, $string, int $boost = null, $analyzer = null) {
        $res =   [
            'match' => [
                $key => [
                    'query' => $string
                ]
            ]
        ];

        if($analyzer)
            $res['match'][$key]['analyzer'] = $analyzer;

        if($boost)
            $res['match'][$key]['boost'] = $boost;

        return $res;
    }

    /**
     * 生成单个term
     * @param $key
     * @param $value
     * @param int $boost
     * @return array
     */
    public function term(string $key, $value, $boost = null) {
        $res = [
            'term' => [
                $key => [
                    'value' => $value
                ]
            ]
        ];

        if($boost)
            $res['term'][$key]['boost'] = $boost;

        return $res;
    }

    public function terms(string $key, $value, $boost = null) {
        $res = [
            'terms' => [
                $key => $value
            ]
        ];

        if($boost)
            $res['terms'][$key]['boost'] = $boost;

        return $res;
    }

    public function prefix(string $key, $value, $boost = null) {
        $res = [
            'prefix' => [
                $key => [
                    'value' => $value
                ]
            ]
        ];

        if($boost)
            $res['prefix'][$key]['boost'] = $boost;

        return $res;
    }

    public function range(string $key, array $conditions, $boost = null, array $config = []) {
        $tokenMap = [
            '<=' => 'lte',
            '<' => 'lt',
            '>=' => 'gte',
            '>' => 'gt',
        ];

        foreach($conditions as $condition) {
            /** @var string[] $tokens */
            $tokens = array_filter(explode(' ', $condition), function($oneConditon) {
                return $oneConditon != '';
            });

            if(isset($tokenMap[$tokens[0]]))
                $config[$tokenMap[$tokens[0]]] = $tokens[1];
        }

        $res =  [
            'range' => [
                $key => $config
            ]
        ];

        if($boost)
            $res['range'][$key]['boost'] = $boost;

        return $res;
    }

    public function andX(array $query1, array $query2) {
        $queries = func_get_args();

        $filters = [];

        foreach($queries as $query) {
            if(isset($query['bool']) && isset($query['bool']['filter']) && !isset($query['bool']['should'])) {
                foreach($query['bool']['filter'] as $subFilter)
                    $filters[] = $subFilter;
            } else {
                $filters[] = $query;
            }
        }

        return [
            'bool' => [
                'filter' => $filters
            ]
        ];
    }

    public function orX(array $query1, array $query2) {
        $queries = func_get_args();

        $clauses = [];

        foreach($queries as $query) {
            if(isset($query['bool']) && !isset($query['bool']['filter']) && isset($query['bool']['should'])) {
                foreach($query['bool']['should'] as $subClause)
                    $clauses[] = $subClause;
            } else {
                $clauses[] = $query;
            }
        }

        return [
            'bool' => [
                'should' => $clauses,
                'minimum_should_match' => 1
            ]
        ];
    }

    public function notX(array $query) {
        return [
            'bool' => [
                'must_not' => [
                    $query
                ]
            ]
        ];
    }

    public function sort(array $params) {
        return array_map(function($item) use ($params){
            return [
                'order' => $item
            ];
        }, $params);
    }
}