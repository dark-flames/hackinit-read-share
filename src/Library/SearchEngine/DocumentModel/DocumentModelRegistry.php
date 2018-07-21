<?php
namespace ReadShare\Library\SearchEngine\DocumentModel;


class DocumentModelRegistry {
    private $registry = [];

    public function __construct(CommentDocumentModel $commentDocumentModel) {
        //Register your querybuilders here
        $this->register($commentDocumentModel);
    }

    private function register(AbstractDocumentModel $builder) {
        $this->registry[$builder->getType()] = $builder;
    }

    public function get(int $builderType):AbstractDocumentModel {
        return $this->registry[$builderType];
    }

    public function has(int $builderType) {
        return isset($this->registry[$builderType]);
    }

    public function getRegistry() {
        return $this->registry;
    }
}