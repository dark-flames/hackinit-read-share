<?php

namespace ReadShare\Library\SearchEngine\Normalizer;

class LowerCaseNormalizer extends AbstractNormalizer {
    public function getNormalizerName(): string {
        return Normalizer::LowerCaseNormalizer;
    }

    public function getNormalizer(): array {
        return [
            "type" => "custom",
            "filter" => ["lowercase"]
        ];
    }
}