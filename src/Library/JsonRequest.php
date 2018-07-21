<?php
namespace ReadShare\Library;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class JsonRequest {
    private $data;

    public function __construct(RequestStack $requestStack) {
        $request = $requestStack->getCurrentRequest();

        if ($request->getContentType() != 'json')
            throw new BadRequestHttpException("Not a Json Request");

        $content = $request->getContent();
        $this->data = json_decode($content, true);
    }

    public function getData() {
        return $this->data;
    }
}