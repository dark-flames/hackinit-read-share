<?php

namespace ReadShare\Library\SearchEngine\TokenFilter;


class EnglishStemmer extends  AbstractCustomTokenFilter {
    public function getTokenFilterName(): string {
        return TokenFilter::EnglishStemmer;
    }

    public function getTokenFilter(): array {
        return [
            "type" => "stemmer",
            "language" =>  "english"
        ];
    }
}