<?php

namespace  ReadShare\Controller;

use ReadShare\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymonfyAbstractController;

class AbstractController extends SymonfyAbstractController {
    protected function renderFrontendApp(string $templateName, $title, $data, int $code = 200) {
        $injection = [
            'code' => $code,
            'templateName' => $templateName,
            'currentData' => $data,
            'currentUser' => null
        ];

        /** @var User $user */
        $user = $this->get('security.token_storage')
            ->getToken()
            ->getUser();

        if($user instanceof User)
            $injection['currentUser'] = $user->detailJsonSerialize();

        return $this->render('application.html.twig', [
            '_injection' => $injection,
            'title' => $title
        ]);
    }
}