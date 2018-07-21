<?php
namespace ReadShare\Library\SearchEngine\Normalizer;

class NormalizerRegistry {
    private static $instance;

    private $registry = [];

    public function __construct() {
        self::$instance = $this;
        $this->register(new LowerCaseNormalizer());
    }

    private function register(AbstractNormalizer $analyzer) {
        $this->registry[$analyzer->getNormalizerName()] = $analyzer;
    }

    public function get(string $analyzerName) : AbstractNormalizer {
        return $this->registry[$analyzerName];
    }

    public function has(string $analyzerName) {
        return isset($this->registry[$analyzerName]);
    }

    public function getRegistry() {
        return $this->registry;
    }

    public static function getInstance(): NormalizerRegistry {
        return self::$instance ?? new self;
    }
}