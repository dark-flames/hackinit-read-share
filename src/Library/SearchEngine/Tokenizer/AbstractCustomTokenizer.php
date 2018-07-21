<?php
namespace ReadShare\Library\SearchEngine\Tokenizer;

abstract class AbstractCustomTokenizer {
    /**
     * 获取自定义分词器对应的名称
     * 须在Analyzer中对应，并修改Analyzer的isDefaultAnalyzer()
     * @return string
     */
    abstract function getTokenizerName(): string;

    /**
     * 获取分词器对象
     * @return array
     */
    abstract function getTokenizer(): array ;
}