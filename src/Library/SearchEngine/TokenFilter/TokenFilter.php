<?php

namespace ReadShare\Library\SearchEngine\TokenFilter;

class TokenFilter {
    const Standard = 'standard';
    const LowerCase = 'lowercase';

    const EnglishStop = 'english_stop';
    const EnglishStemmer = 'english_stemmer';
    const EnglishPossessiveStemmer = "english_possessive_stemmer";

    const CustomFilter = [
        self::EnglishStop,
        self::EnglishStemmer,
        self::EnglishPossessiveStemmer
    ];

    static function isDefaultFilter(string $name) {
        return !in_array($name, self::CustomFilter);
    }
}