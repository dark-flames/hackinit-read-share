<?php
namespace ReadShare\Library\SearchEngine\TokenFilter;

abstract class AbstractCustomTokenFilter {

    abstract function getTokenFilterName(): string;

    abstract function getTokenFilter(): array ;
}