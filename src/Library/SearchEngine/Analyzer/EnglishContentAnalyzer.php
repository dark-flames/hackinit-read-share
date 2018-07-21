<?php

namespace ReadShare\Library\SearchEngine\Analyzer;


use ReadShare\Library\SearchEngine\TokenFilter\EnglishPossessiveStemmer;
use ReadShare\Library\SearchEngine\TokenFilter\TokenFilter;
use ReadShare\Library\SearchEngine\Tokenizer\Tokenizer;

class EnglishContentAnalyzer extends AbstractCustomAnalyzer {
    public function getAnalyzerName(): string {
        return Analyzer::EnglishContent;
    }

    public function getAnalyzer(): array {
        return [
            "tokenizer" => Tokenizer::Standard,
            "filter" => [
                TokenFilter::EnglishPossessiveStemmer,
                TokenFilter::LowerCase,
                TokenFilter::EnglishStop,
                TokenFilter::EnglishStemmer
            ],
            "char_filter" => [
                CharacterFilters::HtmlStrip
            ]
        ];
    }
}