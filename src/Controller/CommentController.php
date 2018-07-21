<?php
namespace ReadShare\Controller;

use ReadShare\Library\JsonRequest;
use ReadShare\Library\JsonResponse;
use ReadShare\Library\SearchEngine\Analyzer\Analyzer;
use ReadShare\Library\SearchEngine\DocumentModel\CommentDocumentModel;
use ReadShare\Library\SearchEngine\SearchEngine;
use ReadShare\Service\CommentManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentController extends AbstractController {
    public function newCommentAction(JsonRequest $jsonRequest, CommentManager $manager, UserInterface $user) {
        try {
            $manager->newComments($user, $jsonRequest['content'], $jsonRequest['targetContent']);
        } catch (\Exception $e) {
            return new JsonResponse([], 500);
        }

        return new JsonResponse([], 200);
    }

    public function getCommentAction(JsonRequest $jsonRequest, CommentManager $manager, SearchEngine $searchEngine) {
        $targetContent = $jsonRequest['targetContent'];

        $qb = $searchEngine->getQueryBuilder()->from(CommentDocumentModel::class);

        $qb->where($qb->expr()->match('content', $targetContent, 1, Analyzer::ChineseSmart));

        $comments = $searchEngine->queryNew($qb->getQuery());

        return new JsonResponse(['comments'=> $comments]);
    }
}