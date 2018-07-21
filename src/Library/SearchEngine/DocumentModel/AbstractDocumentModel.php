<?php
namespace ReadShare\Library\SearchEngine\DocumentModel;

use Doctrine\ORM\EntityManagerInterface;
use ReadShare\Library\SearchEngine\TokenFilter\AbstractCustomTokenFilter;
use ReadShare\Library\SearchEngine\TokenFilter\CustomTokenFilterRegistry;
use ReadShare\Library\SearchEngine\Normalizer\AbstractNormalizer;
use ReadShare\Library\SearchEngine\Normalizer\Normalizer;
use ReadShare\Library\SearchEngine\Normalizer\NormalizerRegistry;
use ReadShare\Library\SearchEngine\QueryExpressionBuilder;
use ReadShare\Library\SearchEngine\Analyzer\AbstractCustomAnalyzer;
use ReadShare\Library\SearchEngine\Analyzer\Analyzer;
use ReadShare\Library\SearchEngine\Analyzer\CustomAnalyzerRegistry;
use ReadShare\Library\SearchEngine\QueryBuilder;
use ReadShare\Library\SearchEngine\SearchEngine;
use ReadShare\Library\SearchEngine\Tokenizer\AbstractCustomTokenizer;
use ReadShare\Library\SearchEngine\Tokenizer\CustomTokenizerRegistry;
use ReadShare\Library\SearchEngine\Tokenizer\Tokenizer;
use ReadShare\Library\SearchEngine\SearchableInterface;

abstract class AbstractDocumentModel {
    protected $queryBuilder;
    protected $expr;

    protected $searchEngine;

    protected $entityManager;

    public function __construct(QueryBuilder $qb, QueryExpressionBuilder $expr, EntityManagerInterface $em) {
        $this->queryBuilder = $qb;
        $this->expr = $expr;
        $this->entityManager = $em;
    }

    /**
     * 返回QueryBuilder对应的QueryType
     * @return mixed
     */
    abstract public function getType();

    /**
     * 根据id获得实体
     * @param int $id
     * @return mixed
     */
    abstract public function getEntityByID(int $id);

    /**
     * 获取实体的
     * @param SearchableInterface $entity
     * @return int
     */
    abstract public function getIDByEntity(SearchableInterface $entity): int;

    abstract protected function getEntities($offset, $limit): array;

    /**
     * 更新所有实体
     * 原则上只能在SearchEngine重建索引时调用
     * @param SearchEngine $searchEngine
     * @return mixed
     */
    public function updateDocumentsByEntities(SearchEngine $searchEngine) {
        $offset = 0;
        while(true) {
            $entities = $this->getEntities($offset, 50);

            if(!$entities || empty($entities))
                break;

            foreach ($entities as $entity)
                $searchEngine->updateByEntity($entity);

            $offset ++;
        }
    }

    /**
     * 根据实体获取文档
     * @param SearchableInterface $entity
     * @return array
     */
    abstract public function getDocumentByEntity(SearchableInterface $entity): array;

    /**
     * 获取索引设置
     * 需要跟添加了Searchable的对应model中的getDataForSearch()返回的数据对应
     * @return mixed
     */
    abstract public function getIndexConfig();

    /**
     * 获取所有使用的自定义分析器
     * @return AbstractCustomAnalyzer[]
     */
    public function getCustomAnalyzers() {
        $analyzers = [];
        $customAnalyzerRegistry = CustomAnalyzerRegistry::getInstance();

        foreach($customAnalyzerRegistry->getRegistry() as $analyzer) {
            /** @var AbstractCustomAnalyzer $analyzer */
            $analyzers[$analyzer->getAnalyzerName()] = $analyzer->getAnalyzer();
        }

        return $analyzers;
    }

    public function getCustomTokenizers() {
        $customTokenizerRegistry = CustomTokenizerRegistry::getInstance();

        $tokenizers = [];

        foreach($customTokenizerRegistry->getRegistry() as $tokenizer) {
            /** @var AbstractCustomTokenizer $tokenizers*/
            $tokenizers[$tokenizer->getTokenizerName()] = $tokenizer->getTokenizer();
        }

        return $tokenizers;
    }

    public function getCustomTokenFilter() {
        $customFilterRegistry = CustomTokenFilterRegistry::getInstance();

        $filters = [];

        foreach($customFilterRegistry->getRegistry() as $filter) {
            /** @var AbstractCustomTokenFilter $filter */
            $filters[$filter->getTokenFilterName()] = $filter->getTokenFilter();
        }

        return $filters;
    }

    public function getNormalizers() {
        $normalizers = [];

        $normalizerRegistry = NormalizerRegistry::getInstance();

        foreach($normalizerRegistry->getRegistry() as $normalizer) {
            /** @var AbstractNormalizer $normalizer */
            $normalizers[$normalizer->getNormalizerName()] = $normalizer->getNormalizer();
        }

        return $normalizers;
    }
}

