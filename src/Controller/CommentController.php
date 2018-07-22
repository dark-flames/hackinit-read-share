<?php
namespace ReadShare\Controller;

use ReadShare\Library\JsonRequest;
use ReadShare\Library\JsonResponse;
use ReadShare\Library\SearchEngine\SearchEngine;
use ReadShare\Service\CommentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class CommentController
 * @package ReadShare\Controller
 *
 * @Route("/comment")
 */
class CommentController extends AbstractController {
    /**
     * @param JsonRequest $jsonRequest
     * @param CommentManager $manager
     * @param UserInterface $user
     * @return JsonResponse
     *
     * @Route("/newComment", name="new_comment")
     */
    public function newCommentAction(JsonRequest $jsonRequest, CommentManager $manager, UserInterface $user) {
        $data = $jsonRequest->getData();
        $comment = $manager->newComments($user, $data['content'], $data['targetContent']);

        return new JsonResponse(['comment' => $comment], 200);
    }

    /**
     * @param Request $request
     * @param CommentManager $manager
     * @param SearchEngine $searchEngine
     * @return JsonResponse
     *
     * @Route("/getComments", name="get_comments")
     */
    public function getCommentsAction(Request $request, CommentManager $manager) {
        $targetContent = $request->query->get('targetContent');



        return new JsonResponse(['comments'=> $manager->getCommentsByES($targetContent)]);
    }
}