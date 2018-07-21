<?php
namespace ReadShare\Library\SearchEngine\Tokenizer;

class CustomTokenizerRegistry {
    private static $instance;

    private $registry = [];

    public function __construct() {
        self::$instance = $this;

    }

    private function register(AbstractCustomTokenizer $tokenizer) {
        $this->registry[$tokenizer->getTokenizerName()] = $tokenizer;
    }

    public function get(string $tokenizer):AbstractCustomTokenizer {
        return $this->registry[$tokenizer];
    }

    public function has(string $tokenizer) {
        return isset($this->registry[$tokenizer]);
    }

    public function getRegistry() {
        return $this->registry;
    }

    public static function getInstance(): CustomTokenizerRegistry {
        return self::$instance ?? new self;
    }
}