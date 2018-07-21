<?php
namespace ReadShare\Library\SearchEngine\Analyzer;

use ReadShare\Library\SearchEngine\Tokenizer\Tokenizer;

class ChineseHTMLAnalyzer extends AbstractCustomAnalyzer {
    function getAnalyzerName(): string {
        return Analyzer::ChineseHTML;
    }

    function getAnalyzer(): array {
        return [
            'type' => 'custom',
            'tokenizer' => Tokenizer::ChineseSmart,
            "char_filter" => [ CharacterFilters::HtmlStrip ]
        ];
    }
}