<?php
namespace ReadShare\Library\SearchEngine\Normalizer;

abstract class AbstractNormalizer {

    abstract function getNormalizerName(): string;

    abstract function getNormalizer(): array ;
}