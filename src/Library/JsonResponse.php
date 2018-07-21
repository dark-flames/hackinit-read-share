<?php

namespace ReadShare\Library;

use ReadShare\Library\Frontend\FrontendSerializableInterface;
use Symfony\Component\HttpFoundation\Response;

class JsonResponse extends Response {
    public function __construct(array $data = [], int $status = 200, array $headers = array()) {

        $jsonData = array_map(function($item) {
            if($item instanceof FrontendSerializableInterface) {
                return $item->jsonSerialize();
            } else {
                return $item;
            }
        }, $data);

        parent::__construct(json_encode($jsonData), $status, $headers);
    }
}