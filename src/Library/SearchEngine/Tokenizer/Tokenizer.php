<?php

namespace ReadShare\Library\SearchEngine\Tokenizer;

class Tokenizer {
    const ChineseMaxWord = 'ik_max_word';
    const ChineseSmart = 'ik_smart';
    const Standard = 'standard';

    const CustomTokenizer = [
    ];

    static function isDefaultTokenizer(string $name) {
        return !in_array($name, self::CustomTokenizer);
    }
}