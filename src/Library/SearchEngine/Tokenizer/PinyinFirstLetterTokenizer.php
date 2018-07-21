<?php

namespace ReadShare\Library\SearchEngine\Tokenizer;

class PinyinFirstLetterTokenizer extends AbstractCustomTokenizer {
    public function getTokenizerName(): string {
        return Tokenizer::PinyinFirstLetter;
    }

    public function getTokenizer(): array {
        return [
            'type' => Tokenizer::Pinyin,
            'keep_separate_first_letter' => false,
            'keep_none_chinese_in_first_letter' => true,
            'keep_full_pinyin' => false,
            'keep_none_chinese' => false,
        ];
    }
}