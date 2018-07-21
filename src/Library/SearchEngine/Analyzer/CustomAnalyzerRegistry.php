<?php
namespace ReadShare\Library\SearchEngine\Analyzer;

class CustomAnalyzerRegistry {
    private static $instance;

    private $registry = [];

    public function __construct() {
        self::$instance = $this;
        $this->register(new ChineseHTMLAnalyzer());
        $this->register(new ChineseSortableAnalyzer());
        $this->register(new EnglishContentAnalyzer());
    }

    private function register(AbstractCustomAnalyzer $analyzer) {
        $this->registry[$analyzer->getAnalyzerName()] = $analyzer;
    }

    public function get(string $analyzerName):AbstractCustomAnalyzer {
        return $this->registry[$analyzerName];
    }

    public function has(string $analyzerName) {
        return isset($this->registry[$analyzerName]);
    }

    public function getRegistry() {
        return $this->registry;
    }

    public static function getInstance(): CustomAnalyzerRegistry {
        return self::$instance ?? new self;
    }
}