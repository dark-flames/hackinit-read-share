<?php
namespace ReadShare\Library\Frontend;

interface FrontendSerializableInterface extends \JsonSerializable {
    /**
     * @return array
     */
    public function jsonSerialize(): array;

    /**
     * @param bool $withAccess
     * @return array
     */
    public function detailJsonSerialize(bool $withAccess = false): array;
}