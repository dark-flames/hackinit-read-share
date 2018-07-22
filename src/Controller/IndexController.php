<?php
namespace ReadShare\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ReadShare\Entity\User;

class IndexController extends AbstractController {
    /**
     * @Route("/app", name="app")
     * @return Response
     */
    public function appAction() {
        return $this->renderFrontendApp(
            'test',
            'ReadShare',
            ['version' => '0.0.1']
        );
    }
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function indexAction(Request $request) {
        /** @var User $user */
        $user = $this->get('security.token_storage')
            ->getToken()
            ->getUser();

        if($user instanceof User)
            return $this->redirectToRoute('app');
        else
            return $this->redirectToRoute('login');
    }
}