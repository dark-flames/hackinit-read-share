<?php

namespace ReadShare\Service;

use Doctrine\ORM\EntityManagerInterface;
use ReadShare\Entity\Comment;
use ReadShare\Entity\User;
use ReadShare\Library\SearchEngine\SearchEngine;
use ReadShare\Library\SearchEngine\Analyzer\Analyzer;
use ReadShare\Library\SearchEngine\DocumentModel\CommentDocumentModel;

class CommentManager {
    private $entityManager;

    private $searchEngine;

    public function __construct(EntityManagerInterface $entityManager, SearchEngine $searchEngine) {
        $this->entityManager = $entityManager;
        $this->searchEngine = $searchEngine;
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getComments(array $ids): array {
        $qb = $this->entityManager->createQueryBuilder();

        if(empty($ids))
            return [];
        /** @var Comment[] $comments */
        $comments = $qb->select('c.*')
            ->from(Comment::class, 'c')
            ->where($qb->expr()->in('c.id', ':ids'))
            ->setParameter('ids', $ids)
            ->getQuery()
            ->useQueryCache(true)
            ->getResult();

        return $comments;
    }

    public function newComments(User $user, string $content, string $targetContent) {
        $comment = new Comment();
        $comment->setAuthor($user)
            ->setContent($content)
            ->setTargetContent($targetContent);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
        $this->searchEngine->updateByEntity($comment);

        return $comment;
    }

    public function getCommentsByES(string $targetContent) {
        $qb = $this->searchEngine->getQueryBuilder()->from(CommentDocumentModel::getType());

        $qb->match($qb->expr()->match('content', $targetContent, null,Analyzer::ChineseSmart));

        $filterFactory = function (array $items) {
            $scores = array_map(function($item) {
                return $item['_score'];
            }, $items);

            $maxID = 0;
            $maxDifference = -1;
            foreach ($scores as $rank => $score) {
                if(isset($scores[$rank+1])) {
                    if($score - $scores[$rank+1] > $maxDifference) {
                        $maxID = $rank;
                        $maxDifference = $score - $scores[$rank+1];
                    }
                }
            }

            $splitScore = $scores[$maxID];

            return function ($item) use ($splitScore) {
                return $item['_score'] >= $splitScore;
            };
        };

        $comments = $this->searchEngine->queryNew($qb->getQuery(), $filterFactory);
        return $comments;
    }
}