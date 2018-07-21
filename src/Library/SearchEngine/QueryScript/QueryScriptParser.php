<?php

namespace ReadShare\Library\SearchEngine\QueryScript;

use ReadShare\Library\SearchEngine\DocumentModel\DocumentModelType;
use ReadShare\Library\SearchEngine\QueryBuilder;

class QueryScriptParser {
    /**
     * @var array
     */
    private $script;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var array
     */
    private $subQueryRegistry = [];

    /**
     * @param $queryName
     * @return bool
     */
    private function hasSubQuery($queryName) {
        return isset($this->subQueryRegistry[$queryName]);
    }

    /**
     * @param $queryName
     * @return mixed
     */
    private function getSubQuery($queryName) {
        return $this->subQueryRegistry[$queryName]['query'];
    }

    /**
     * @param $queryName
     * @return mixed
     */
    private function getSubQueryStatus($queryName) {
        return $this->subQueryRegistry[$queryName]['status'];
    }

    /**
     * @param $queryName
     * @param $status
     * @param null $query
     */
    private function updateSubQuery($queryName, $status, $query = null) {
        $this->subQueryRegistry[$queryName]['status'] = $status;
        if($query)
            $this->subQueryRegistry[$queryName]['query'] = $query;
    }

    /**
     * @param $queryName
     * @param $query
     * @param $status
     */
    private function registerSubQuery($queryName, $query, $status) {
        $this->subQueryRegistry[$queryName] = [
            'query' => $query,
            'status' => $status
        ];
    }

    /**处理类型，分页，排序等基本信息
     * @throws QueryScriptParseException
     */
    private function handleBasicInfo() {
        if(!isset($this->script['type']))
            throw new QueryScriptParseException("Parse error: DocumentType Not Found");

        else
            $this->queryBuilder->from(DocumentModelType::getType($this->script['type']));

        if(isset($this->script['offset']))
            $this->queryBuilder->setFirstResult($this->script['offset']);

        if(isset($this->script['limit']))
            $this->queryBuilder->setMaxResults($this->script['limit']);

        if(isset($this->script['sortBy'])) {
            foreach($this->script['sortBy'] as $sortBy => $order) {
                if(!in_array($order, ['desc', 'asc']))
                    throw new QueryScriptParseException('Parser error: Unexpected order ' . $order);

                $this->queryBuilder->orderBy($sortBy);
            }
        }
    }

    /**初步解析子查询，需要分析的标记为待处理
     * @throws QueryScriptParseException
     */
    private function parseQueries() {
        if(!isset($this->script['queries']))
            return;

        if(count($this->script['queries']) > 64)
            throw new QueryScriptParseException("Parse error: Too many queries");

        foreach($this->script['queries'] as $queryName => $query) {
            if(!preg_match('/^[a-z][a-z0-9_\\-]*/i', $queryName))
                throw new QueryScriptParseException("Parse error: Unexpected query name: " . $queryName);

            if(is_array($query)) {
                $subQuery = null;
                foreach(['method', 'key', 'value'] as $field) {
                    if(!isset($query[$field]))
                        throw new QueryScriptParseException("Parse error: Unknown query " . $field . " in query:" . $queryName);
                }

                switch($query['method']) {
                    case 'match':
                        $subQuery = $this->queryBuilder->expr()->match(
                            $query['key'],
                            $query['value'],
                            $query['boost'] ?? null,
                            $query['analyzer'] ?? null
                        );
                        break;
                    case 'term':
                        $subQuery = $this->queryBuilder->expr()->term(
                            $query['key'],
                            $query['value'],
                            $query['boost'] ?? null
                        );
                        break;
                    case 'terms':
                        if(!is_array($query['value']))
                            throw new QueryScriptParseException("Parse error: Field value in query" . $queryName . " must be type of array");

                        $subQuery = $this->queryBuilder->expr()->terms(
                            $query['key'],
                            $query['value'],
                            $query['boost'] ?? null
                        );
                        break;
                    case 'prefix':
                        $subQuery = $this->queryBuilder->expr()->prefix(
                            $query['key'],
                            $query['value'],
                            $query['boost'] ?? null
                        );
                        break;
                    case 'range':
                        if(!is_array($query['value']))
                            throw new QueryScriptParseException("Parse error: Field value in query" . $queryName . " must be type of array");

                        $subQuery = $this->queryBuilder->expr()->prefix(
                            $query['key'],
                            $query['value'],
                            $query['boost'] ?? null
                        );
                        break;
                }

                $this->registerSubQuery($queryName, $subQuery, SubQueryStatus::FinishHandle);
            }
            else if(is_string($query)) {
                $this->registerSubQuery($queryName, $query, SubQueryStatus::PendingHandle);
            } else {
                throw new QueryScriptParseException("Parse error: Unknown query:" . json_encode($query));
            }
        }
    }

    /**处理子查询的关系，找到依赖的子查询并分析
     * @throws QueryScriptParseException
     */
    private function handleFiltersAndMatches() {
        $handleStack = new \SplStack(); //维护待处理的查询
        //将filter和match入栈
        if(isset($this->script['filter']) && $this->script['filter']) {
            $this->registerSubQuery("__filter", $this->script['filter'], SubQueryStatus::PendingHandle);
            $handleStack->push("__filter");
        }

        if(isset($this->script['match']) && $this->script['match']) {
            $this->registerSubQuery("__match", $this->script['match'], SubQueryStatus::PendingHandle);
            $handleStack->push("__match");
        }

        while(!$handleStack->isEmpty()) {
            $subQueryName = $handleStack->top();

            if($this->getSubQueryStatus($subQueryName) == SubQueryStatus::FinishHandle) {//如果已经处理完，直接跳过
                $handleStack->pop();
                continue;
            }

            $parser = new QuerySyntaxParser($this->subQueryRegistry[$subQueryName]['query']); //建立语法树

            $this->updateSubQuery($subQueryName, SubQueryStatus::Handling); //标记为为正在处理

            $dependentSubQueries = $parser->getDependentSubQueryName(); //获得依赖的子查询

            $executeNow = true;

            $subQueries = [];

            foreach($dependentSubQueries as $dependentSubQuery) {
                if(!$this->hasSubQuery($dependentSubQuery)) //未定义的子查询
                    throw new QueryScriptParseException("Undefined SubQuery:". $dependentSubQuery);

                $status = $this->getSubQueryStatus($dependentSubQuery);

                if($status == SubQueryStatus::Handling) //如果子查询正在处理，则认为发生循环依赖
                    throw new QueryScriptParseException(
                        "Parse Error: Query ". $subQueryName . " is dependent on Query " . $dependentSubQuery . ", But it`s handling"
                    );

                else if($status == SubQueryStatus::PendingHandle) { //有依赖的子查询还未被处理，压入栈中
                    $executeNow = false;
                    $handleStack->push($dependentSubQuery);
                } else {
                    $subQueries[$dependentSubQuery] = $this->getSubQuery($dependentSubQuery); //记录子查询
                }
            }

            if($executeNow) { //如果所有依赖的子查询都已经被处理，处理该查询并弹栈
                $this->updateSubQuery($subQueryName, SubQueryStatus::FinishHandle, $parser->getResult($subQueries));
                $handleStack->pop();
            }
        }

        if(isset($this->script['filter']) && $this->script['filter']) {
            $this->queryBuilder->where($this->getSubQuery("__filter"));
        }

        if(isset($this->script['match']) && $this->script['match']) {
            $this->queryBuilder->match($this->getSubQuery("__match"));
        }
    }

    /**解析查询
     * @param string $script
     * @return QueryBuilder
     * @throws QueryScriptParseException
     */
    public function parse(string $script):QueryBuilder {
        $this->queryBuilder = new QueryBuilder();
        try {
            $this->script = json_decode($script, true);
            //throw new \Exception(var_export($script, true));
        } catch(\Exception $e) {
            throw new QueryScriptParseException($e->getMessage());
        }


        $this->handleBasicInfo();

        $this->parseQueries();
        $this->handleFiltersAndMatches();

        return $this->queryBuilder;
    }
}

/**子查询处理状态
 * Class SubQueryStatus
 * @package ReadShare\Library\SearchEngine\QueryScript
 */
class SubQueryStatus {
    const FinishHandle = 0;
    const Handling = 1;
    const PendingHandle = 2;
}