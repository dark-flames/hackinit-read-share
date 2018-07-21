<?php
namespace ReadShare\Library\SearchEngine\Analyzer;

use ReadShare\Library\SearchEngine\Tokenizer\Tokenizer;

class ChineseSortableAnalyzer extends AbstractCustomAnalyzer {
    function getAnalyzerName(): string {
        return Analyzer::ChineseSortable;
    }

    function getAnalyzer(): array {
        return [
            'type' => 'custom',
            'tokenizer' => Tokenizer::PinyinFirstLetter
        ];
    }
}