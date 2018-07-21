<?php

namespace ReadShare\Library\SearchEngine;

use ReadShare\Library\SearchEngine\DocumentModel\DocumentModelRegistry;
use ReadShare\Library\SearchEngine\DocumentModel\DocumentModelType;
use ReadShare\Library\SearchEngine\QueryExpressionBuilder;
use ReadShare\Library\SearchEngine\SearchEngine;
use ReadShare\Library\SearchEngine\SearchableInterface;

class QueryBuilder {
    private $query;

    private $limit;

    private $offset;

    private $filters;

    private $clauses;

    private $minShouldMatch;

    private $sort;
    /**
     * @var QueryExpressionBuilder
     */
    private $expr;

    public function __construct(QueryExpressionBuilder $expr) {
        $this->clear();
        $this->expr = $expr;
    }

    public function expr() {
        return $this->expr;
    }

    public function setMaxResults(int $limit): QueryBuilder {
        $this->limit = $limit;

        return $this;
    }

    public function setFirstResult(int $offset): QueryBuilder {
        //$this->query['from'] = $offset;

        $this->offset = $offset;

        return $this;
    }

    public function from(int $documentType): QueryBuilder {
        $typeName = DocumentModelType::toString($documentType);

        $this->query['index'] = SearchEngine::IndexName . $typeName;
        $this->query['type'] = $typeName;

        return $this;
    }

    public function where(array $where): QueryBuilder {
        $this->filters = [
            'bool' => [
                'filter' => [$where]
            ]
        ];

        return $this;
    }

    public function andWhere(array $where): QueryBuilder {
        $this->filters = $this->expr()->andX($this->filters, $where);

        return $this;
    }

    public function orWhere(array $where): QueryBuilder {
        $this->filters = $this->expr()->orX($this->filters, $where);

        return $this;
    }

    public function match(array $match): QueryBuilder {
        $this->clauses = [ $match ];

        return $this;
    }

    public function addMatch(array $match): QueryBuilder {
        $this->clauses[] = $match;

        return $this;
    }

    public function setMinShouldMatch(int $minShouldMatch): QueryBuilder {
        $this->minShouldMatch = $minShouldMatch;

        return $this;
    }

    public function orderBy(string $sort, $order = 'asc'): QueryBuilder {
        $this->sort = [
            $sort => [
                'order' => $order
            ]
        ];

        return $this;
    }

    public function addOrderBy(string $sort, $order = 'asc'): QueryBuilder {
        $this->sort[$sort] = [
            'order' => $order
        ];

        return $this;
    }

    public function clear(): QueryBuilder {
        $this->minShouldMatch = 1;
        $this->filters = [];
        $this->clauses = [];
        $this->query = [];
        $this->offset = null;
        $this->limit = null;

        return $this;
    }

    public function getQuery(bool $count = false) {
        $queryBody = [
            'query' => [
                'bool' => []
            ]
        ];

        if(!empty($this->sort) && !$count) {
            $queryBody['sort'] = $this->sort;
        }

        if(!empty($this->filters)) {
            $queryBody['query']['bool']['filter'] = [$this->filters];
        }

        if(!empty($this->clauses)) {
            $queryBody['query']['bool']['should'] = $this->clauses;
            $queryBody['query']['bool']['minimum_should_match'] = $this->minShouldMatch;
        }

        $result = $this->query;

        $result['body'] = $queryBody;

        if($this->offset && !$count)
            $result['from'] = $this->offset;

        if($this->limit && !$count)
            $result['size'] = $this->limit;

        return $result;
    }
}