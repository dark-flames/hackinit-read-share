<?php
namespace ReadShare\Library\SearchEngine\DocumentModel;

use ReadShare\Entity\Comment;
use ReadShare\Library\SearchEngine\Analyzer\Analyzer;
use ReadShare\Library\SearchEngine\SearchableInterface;
use ReadShare\Library\SearchEngine\SearchEngine;

class CommentDocumentModel extends AbstractDocumentModel {
    public static function getType() {
        return DocumentModelType::Comment;
    }

    public function getEntityByID(int $id) {
        return $this->entityManager
            ->getRepository(Comment::class)
            ->find($id);
    }

    public function getIDByEntity(SearchableInterface $entity): int {
        /** @var Comment $entity */
        return $entity->getId();
    }

    public function getDocumentByEntity(SearchableInterface $entity): array {
        /** @var Comment $entity */
        return [
            'content' => $entity->getTargetContent()
        ];
    }

    public function getIndexConfig() {
        return [
            'content' => [
                'type' => 'text',
                'analyzer' => Analyzer::ChineseMaxWord,
                'term_vector' => 'yes'
            ],

        ];
    }

    protected function getEntities($offset, $limit): array {
        $qb = $this->entityManager->createQueryBuilder();

        return $qb->select('c')
            ->from(Comment::class, 'c')
            ->where('1=1')
            ->setMaxResults($limit)
            ->setFirstResult($offset * $limit)
            ->getQuery()
            ->useQueryCache(true)
            ->getResult();
    }

}