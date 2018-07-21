<?php

namespace ReadShare\Library\SearchEngine\TokenFilter;


class EnglistStopTokenFilter extends AbstractCustomTokenFilter {
    public function getTokenFilterName(): string {
        return TokenFilter::EnglishStop;
    }

    public function getTokenFilter(): array {
        return [
            "type" => "stop",
            "stopwords" => "_english_"
        ];
    }
}