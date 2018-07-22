<?php

namespace ReadShare\Library;

use ReadShare\Library\Frontend\FrontendSerializableInterface;
use Symfony\Component\HttpFoundation\Response;

class JsonResponse extends Response {
    public function __construct(array $data = [], int $status = 200, array $headers = array()) {
        parent::__construct(json_encode($data), $status, $headers);
    }
}