<?php
namespace ReadShare\Library\SearchEngine\Analyzer;

abstract class AbstractCustomAnalyzer {
    /**
     * 获取自定义分析器对应的名称
     * 须在Analyzer中对应，并修改Analyzer的isDefaultAnalyzer()
     * @return string
     */
    abstract function getAnalyzerName(): string;

    /**
     * 获取分析器对象
     * @return array
     */
    abstract function getAnalyzer(): array ;
}