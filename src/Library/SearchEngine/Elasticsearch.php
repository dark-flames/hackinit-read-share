<?php
namespace ReadShare\Library\SearchEngine;

use Elasticsearch\ClientBuilder;

/**
 * 初始化ES的ClientBuilder
 * Class Elasticsearch
 * @package ReadShare\Library\SearchEngine
 */
class Elasticsearch {
    public $instance;

    private static $_instance;

    public function __construct($esHost) {
        self::$_instance = $this;

        $hosts = [
            $esHost
        ];


        $this->instance = ClientBuilder::create()->setHosts($hosts)->build();
    }

    public function getInstance(): Elasticsearch {
        return $this->instance;
    }
}