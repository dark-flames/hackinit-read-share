<?php

namespace ReadShare\Library\SearchEngine\QueryScript;

use ReadShare\Library\SearchEngine\QueryExpressionBuilder;
use LuoguFramework\ServiceProvider\Cache\DoctrineCacheServiceProvider;
use Tmilos\Lexer\Config\LexerArrayConfig;
use Tmilos\Lexer\Config\TokenDefn;
use Tmilos\Lexer\Error\UnknownTokenException;
use Tmilos\Lexer\Lexer;
use Tmilos\Lexer\Token;

class QuerySyntaxParser {
    /**
     * @var QuerySyntaxTree|null
     */
    private $snytaxTree;
    /**
     * @var QueryExpressionBuilder
     */
    private $expr;

    private $_dependentSubQueryName = null;

    /**对于相同的查询缓存语法树
     * QuerySyntaxParser constructor.
     * @param $query
     * @throws QueryScriptParseException
     */
    public function __construct($query) {
        $this->buildSnytaxTree($this->lex($query));
        $this->expr = QueryExpressionBuilder::getInstance();
    }

    /**
     * @param \SplStack $operatorStack
     * @param \SplStack $operandStack
     */
    private static function popStack(\SplStack $operatorStack, \SplStack $operandStack) {
        /** @var Token|QuerySyntaxTree $rightToken */
        $rightToken = $operandStack->pop();
        /** @var Token $operation */
        $operation = $operatorStack->pop();

        if($operation->getName() != 'not')
            $leftToken = $operandStack->pop();
        else
            $leftToken = null;

        $node = new QuerySyntaxTree($operation->getName());

        if($rightToken instanceof Token)
            $rightToken = $rightToken->getValue();

        if($leftToken instanceof Token)
            $leftToken = $leftToken->getValue();

        if($leftToken)
            $node->addChilderNode($leftToken);

        $node->addChilderNode($rightToken);

        $operandStack->push($node);
    }

    /**建立语法树
     * @param array $tokens
     */
    private function buildSnytaxTree(array $tokens) {
        /**@var Token[] $tokens*/
        $this->snytaxTree = new QuerySyntaxTree('master');

        $operatorTokenStack = new \SplStack();
        $operandTokenStack = new \SplStack();

        foreach($tokens as $key => $token) {
            if(in_array($token->getName(),['and', 'or'])) {
                while(!$operatorTokenStack->isEmpty() && $operatorTokenStack->top()->getName() != 'leftParenthese')
                    self::popStack($operatorTokenStack, $operandTokenStack);

                $operatorTokenStack->push($token);
            }
            else if($token->getName() == 'leftParenthese') {
                $operatorTokenStack->push($token);
            }
            else if($token->getName() == 'rightParenthese') {
                while(!$operatorTokenStack->isEmpty() && $operatorTokenStack->top()->getName() != 'leftParenthese')
                    self::popStack($operatorTokenStack, $operandTokenStack);
                if($operatorTokenStack->isEmpty())
                    throw new \QueryScriptParseException('Parse Error: Unexpected token \')\'');

                else
                    $operatorTokenStack->pop();
            }
            else if($token->getName() == 'subQuery') {
                $operandTokenStack->push($token);
            }
            else if($token->getName() == 'not') {
                $operatorTokenStack->push($token);
            } else {
                throw new \QueryScriptParseException("Unrecognized token");
            }
        }
        while(!$operatorTokenStack->isEmpty())
            self::popStack($operatorTokenStack, $operandTokenStack);
        $node = $operandTokenStack->pop();

        if($node instanceof QuerySyntaxTree)
            $this->snytaxTree = $node;
        else {
            $this->snytaxTree = new QuerySyntaxTree('single-node');
            $this->snytaxTree->addChilderNode($node->getValue());
        }
    }

    /**词法分析
     * @param string $query
     * @return \Tmilos\Lexer\Token[]
     */
    private function lex(string $query) {
        $lexerConfig = new LexerArrayConfig([
            '\\s' => '',
            'AND' => 'and',
            'OR' => 'or',
            'NOT' => 'not',
            '\\(' => 'leftParenthese',
            '\\)' => 'rightParenthese',
            '[a-z][a-z0-9_\\-]*' => 'subQuery'
        ]);
        try {
            $tokens = Lexer::scan($lexerConfig, $query);

        } catch(UnknownTokenException $e) {
            throw new QueryScriptParseException($e->getMessage());
        }


        return $tokens;
    }

    /**
     * @param QuerySyntaxTree $node
     * @return array
     */
    private function getDependentSubQueryNameByNode(QuerySyntaxTree $node) {
        $subQuery = [];
        foreach($node->getChilderNodes() as $child) {
            if($child instanceof QuerySyntaxTree)
                $subQuery = array_merge($subQuery, $this->getDependentSubQueryNameByNode($child));
            else
                $subQuery[] = $child;
        }

        return array_unique($subQuery);
    }

    /**获取所有依赖的子查询
     * @return array|null
     */
    public function getDependentSubQueryName() {
        if($this->_dependentSubQueryName)
            return $this->_dependentSubQueryName;

        else {
            $this->_dependentSubQueryName = $this->getDependentSubQueryNameByNode($this->snytaxTree);
            return $this->_dependentSubQueryName;
        }
    }

    /**
     * @param QuerySyntaxTree $node
     * @param array $subQueries
     * @return array|mixed
     * @throws QueryScriptParseException
     */
    private function getResultByNode(QuerySyntaxTree $node,array $subQueries) {
        if($node->getNodeType() == 'not') {
            $subQueryName = $node->getChilderNodes()[0];
            $subQuery = null;

            if($subQueryName instanceof QuerySyntaxTree)
                $subQuery = $this->getResultByNode($subQueryName, $subQueries);
            else if(!isset($subQueries[$subQueryName]))
                throw new QueryScriptParseException("Undefined SubQuery:" . $subQueryName);
            else
                $subQuery =$subQueries[$subQueryName];

            return $this->expr->notX($subQuery);
        }
        else if(in_array($node->getNodeType(), ['and', 'or'])){
            $rightSubQueryName = $node->getChilderNodes()[0];
            $leftSubQueryName = $node->getChilderNodes()[1];

            $rightSubQuery = null;
            $leftSubQuery = null;

            if($rightSubQueryName instanceof QuerySyntaxTree)
                $rightSubQuery = $this->getResultByNode($rightSubQueryName, $subQueries);
            else if(!isset($subQueries[$rightSubQueryName]))
                throw new QueryScriptParseException("Undefined SubQuery:" . $rightSubQueryName);
            else
                $rightSubQuery = $subQueries[$rightSubQueryName];

            if($leftSubQueryName instanceof QuerySyntaxTree)
                $leftSubQuery = $this->getResultByNode($leftSubQueryName, $subQueries);
            else if(!isset($subQueries[$leftSubQueryName]))
                throw new QueryScriptParseException("Undefined SubQuery:" . $leftSubQueryName);
            else
                $leftSubQuery = $subQueries[$leftSubQueryName];

            if(!$leftSubQuery || !$rightSubQuery)
                throw new QueryScriptParseException($subQueries[$leftSubQueryName].'|'.$subQueries[$rightSubQueryName]);
            if($node->getNodeType() == 'and') {
                return $this->expr->andX($leftSubQuery, $rightSubQuery);
            } else {
                return $this->expr->orX($leftSubQuery, $rightSubQuery);
            }
        }
        else if($node->getNodeType() == 'single-node') {
            $subQueryName = $node->getChilderNodes()[0];
            if(!isset($subQueries[$subQueryName]))
                throw new QueryScriptParseException("Undefined SubQuery:" . $subQueryName);

            return $subQueries[$subQueryName];
        }
    }

    /**给定所有子查询的内容，获得查询结果
     * @param array $subQueries
     * @return array|mixed
     * @throws QueryScriptParseException
     */
    public function getResult(array $subQueries) {
        return $this->getResultByNode($this->snytaxTree, $subQueries);
    }
}