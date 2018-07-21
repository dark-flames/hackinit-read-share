<?php

namespace ReadShare\Library\SearchEngine\QueryScript;

class QuerySyntaxTree {
    private $nodeType;
    /**
     * @var QuerySyntaxTree
     */
    private $parentNode;

    private $childrenNodes;

    public const NodeType = [
        'or',
        'and',
        'not',
        'single-node'
    ];

    /**
     * QuerySyntaxTree constructor.
     * @param $nodeType
     */
    public function __construct($nodeType) {
        $this->nodeType = $nodeType;
        $this->setParentNode($this);
        $this->childrenNodes = [];
    }

    /**
     * @param QuerySyntaxTree $node
     */
    public function setParentNode(QuerySyntaxTree $node) {
        $this->parentNode = $node;
    }

    /**
     * @return QuerySyntaxTree
     */
    public function getParentNode() {
        return $this->parentNode;
    }

    public function addChilderNode($node) {
        $this->childrenNodes[] = $node;

        if($node instanceof QuerySyntaxTree)
            $node->setParentNode($this);
    }

    public function getNodeType() {
        return $this->nodeType;
    }

    /**
     * @return array|QuerySyntaxTree[]
     */
    public function getChilderNodes() {
        return $this->childrenNodes;
    }
}