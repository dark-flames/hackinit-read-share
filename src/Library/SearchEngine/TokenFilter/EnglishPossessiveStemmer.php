<?php
/**
 * Created by PhpStorm.
 * User: darkflames
 * Date: 2018/3/31
 * Time: 19:23
 */

namespace ReadShare\Library\SearchEngine\TokenFilter;


class EnglishPossessiveStemmer extends AbstractCustomTokenFilter {
    public function getTokenFilterName(): string {
        return TokenFilter::EnglishPossessiveStemmer;
    }

    public function getTokenFilter(): array {
        return [
            "type" => "stemmer",
            "language" => "possessive_english"
        ];
    }
}