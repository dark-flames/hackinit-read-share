<?php
namespace ReadShare\Library\SearchEngine\TokenFilter;

class CustomTokenFilterRegistry {
    private static $instance;

    private $registry = [];

    public function __construct() {
        self::$instance = $this;

        $this->register(new EnglistStopTokenFilter());
        $this->register(new EnglishStemmer());
        $this->register(new EnglishPossessiveStemmer());
    }

    private function register(AbstractCustomTokenFilter $tokenFilter) {
        $this->registry[$tokenFilter->getTokenFilterName()] = $tokenFilter;
    }

    public function get(string $tokenFilter) : AbstractCustomTokenFilter {
        return $this->registry[$tokenFilter];
    }

    public function has(string $to) {
        return isset($this->registry[$to]);
    }

    public function getRegistry() {
        return $this->registry;
    }

    public static function getInstance(): CustomTokenFilterRegistry{
        return self::$instance ?? new self;
    }
}