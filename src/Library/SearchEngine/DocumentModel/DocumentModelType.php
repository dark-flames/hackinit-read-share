<?php
namespace ReadShare\Library\SearchEngine\DocumentModel;

use Luogu\Models\Blog;
use Luogu\Models\Problem;
use Luogu\Models\User;
use ReadShare\Entity\Comment;

class DocumentModelType {
    const Comment= 1;

    const DocumentModelTypes = [
        self::Comment
    ];

    static function toString($type) {
        $stringArray = [
            self::Comment => 'comment'
        ];

        if(!isset($stringArray, $type))
            throw new \InvalidArgumentException("QueryType is invalid");

        return $stringArray[$type];
    }

    static function getType($type) {
        $stringArray = [
            'comment' => self::Comment
        ];

        if(!isset($stringArray, $type))
            throw new \InvalidArgumentException("QueryType is invalid");

        return $stringArray[$type];
    }

    static function getTypeByEntity($entity) {
        $className = get_class($entity);

        $classNameToType = [
            Comment::class => self::Comment
        ];

        if(!isset($classNameToType, $className))
            throw new \InvalidArgumentException("Entity is invalid");

        return $classNameToType[$className];
    }
}