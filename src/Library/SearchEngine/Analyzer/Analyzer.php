<?php
namespace ReadShare\Library\SearchEngine\Analyzer;

class Analyzer {
    const ChineseMaxWord = "ik_max_word";
    const ChineseSmart = "ik_smart";
    const Standard = "standard";
    const Simple = "simple";
    const Whitespace = "whitespace";
    const Keyword = "keyword";

    const ChineseHTML = "chinese_html";
    const ChineseSortable = "chinese_sortable";
    const EnglishContent = "english_content";

    const CustomAnalyzer = [
        self::ChineseHTML,
        self::ChineseSortable,
        self::EnglishContent
    ];

    static function isDefaultAnalyzer(string $name) {
        return !in_array($name, self::CustomAnalyzer);
    }
}

class CharacterFilters {
    const HtmlStrip = 'html_strip';
}