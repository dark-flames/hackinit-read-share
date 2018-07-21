<?php

namespace ReadShare\Library\SearchEngine\Tokenizer;

class Tokenizer {
    const ChineseMaxWord = 'ik_max_word';
    const ChineseSmart = 'ik_smart';
    const Pinyin = 'pinyin';
    const Standard = 'standard';

    const PinyinFirstLetter = 'pinyin_first_letter';

    const CustomTokenizer = [
        self::PinyinFirstLetter
    ];

    static function isDefaultTokenizer(string $name) {
        return !in_array($name, self::CustomTokenizer);
    }
}