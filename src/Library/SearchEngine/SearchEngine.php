<?php
namespace ReadShare\Library\SearchEngine;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use ReadShare\Library\SearchEngine\DocumentModel\DocumentModelRegistry;
use ReadShare\Library\SearchEngine\DocumentModel\DocumentModelType;
use ReadShare\Library\SearchEngine\SearchableInterface;

class SearchEngine {
    const IndexName = 'read-share:';

    private $elasticsearch;

    private $documentModelRegistry;

    private $expr;

    public function __construct(
        Elasticsearch $es,
        DocumentModelRegistry $documentModelRegistry,
        QueryExpressionBuilder $expr
    ) {
        $this->elasticsearch = $es;
        $this->documentModelRegistry = $documentModelRegistry;
        $this->expr = $expr;
    }

    /**
     * 重建指定类型的索引，会丢失原有的所有数据
     * 原则上只能从console调用
     * @param int $type
     */
    public function buildIndex(int $type) {

        $typeName = DocumentModelType::toString($type);

        //删除原有索引

        try {
            $this->elasticsearch->instance->indices()
                ->delete(['index' => self::IndexName . $typeName]);
        } catch(Missing404Exception $e){}

        $documentModel = $this->documentModelRegistry->get($type);

        $analysis = [];

        //声明所有用到的自定义分析器
        $tokenizers = $documentModel->getCustomTokenizers();
        $analyzers = $documentModel->getCustomAnalyzers();
        $normalizer = $documentModel->getNormalizers();
        $filter = $documentModel->getCustomTokenFilter();

        if(!empty($filter))
            $analysis['filter'] = $filter;

        if(!empty($tokenizers))
            $analysis['tokenizer'] = $tokenizers;

        if(!empty($analyzers))
            $analysis['analyzer'] = $analyzers;

        if(!empty($normalizer))
            $analysis['normalizer'] = $normalizer;


        $indexConfig =  [
            'index' => self::IndexName . $typeName,
            'body' => [
                'mappings' =>[
                    $typeName => [
                        'properties' => $documentModel->getIndexConfig()
                    ]
                ]
            ]
        ];

        if(!empty($analysis))
            $indexConfig['body']['settings']['analysis'] = $analysis;


        //新建索引并更新所有数据
        $this->elasticsearch->instance->indices()->create($indexConfig);
        //$documentModel->updateDocumentsByEntities($this);
    }

    /**
     * 检查指定文档的存在性
     * @param int $type
     * @param $id
     * @return bool
     */
    public function exist(int $type, $id) {
        $typeName = DocumentModelType::toString($type);

        $param = [
            'index' => self::IndexName . $typeName,
            'type' => $typeName,
            'id' => $id
        ];

        try {
            $this->elasticsearch->instance->get($param);
        } catch(Missing404Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * 更新指定文档的数据
     * @param int $type
     * @param $id
     * @param $document
     * @return array
     */
    public function update(int $type, $id, $document) {
        $typeName = DocumentModelType::toString($type);

        $param = [
            'index' => self::IndexName . $typeName,
            'type' => $typeName,
            'id' => $id,
        ];
        if($this->exist($type, $id)) {
            $param['body'] = [
                'doc' => $document
            ];
            return $this->elasticsearch->instance
                ->update($param);
        } else {
            $param['body'] = $document;
            return $this->elasticsearch->instance
                ->index($param);
        }
    }

    /**
     * 删除指定文档
     * @param $type
     * @param $id
     */
    public function delete($type, $id) {
        $typeName = DocumentModelType::toString($type);

        $params = [
            'index' => self::IndexName . $typeName,
            'type' => $typeName,
            'id' => $id
        ];
        $this->elasticsearch->instance->delete($params);
    }

    /**
     * @param array $query
     * @return SearchableInterface[]
     */
    public function queryNew(array $query) {
        $type = $query['type'];
        $documentModel = $this->documentModelRegistry->get(DocumentModelType::getType($type));

        $result = $this->elasticsearch->instance->search($query)['hits']['hits'];

        $entities = [];

        foreach($result as $item) {
            $entities[] = $documentModel->getEntityByID($item['_id']);
        }

        return $entities;
    }

    public function countNew(array $query) {
        $result = $this->elasticsearch->instance->count($query)['count'];

        return $result;
    }

    /**
     * @param SearchableInterface $entity
     */
    public function updateByEntity(SearchableInterface $entity) {
        $documentModel = $this->documentModelRegistry->get(
            DocumentModelType::getTypeByEntity($entity)
        );

        $documentModel->updateDocumentByEntity($entity);
    }

    /**
     * @param SearchableInterface $entity
     */
    public function deleteByEntity(SearchableInterface $entity) {
        $documentModel = $this->documentModelRegistry->get(
            DocumentModelType::getTypeByEntity($entity)
        );

        $documentModel->deleteDocumentByEntity($entity);
    }

    public function getQueryBuilder(): QueryBuilder {
        return new QueryBuilder($this->expr);
    }
}