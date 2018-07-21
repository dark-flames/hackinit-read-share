<?php
namespace ReadShare\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController {
    /**
     * @Route("/test", name="test")
     * @return Response
     */
    public function testAction() {
        return $this->renderFrontendApp(
            'test',
            'test',
            [ 'test' => 'hw']
        );
    }

    /**
     * @Route("/login", name="login")
     *
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request) {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'index/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error' => $error,
            )
        );
    }
}