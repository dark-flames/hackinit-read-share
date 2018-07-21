<?php

namespace ReadShare\Service;

use Doctrine\ORM\EntityManagerInterface;
use ReadShare\Entity\Comment;
use ReadShare\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentManager {
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
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
    }
}