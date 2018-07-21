<?php

namespace ReadShare\Entity;

use Doctrine\ORM\Mapping as ORM;
use ReadShare\Library\Frontend\FrontendSerializableInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 * @ORM\Cache("NONSTRICT_READ_WRITE")
 */
class Comment implements FrontendSerializableInterface {
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="comment_author", referencedColumnName="uid")
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $content;

    /**
     * @var $content
     *
     * @ORM\Column(type="string")
     */
    private $targetContent;

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Comment
     */
    public function setId(int $id): Comment {
        $this->id = $id;
        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): User {
        return $this->author;
    }

    /**
     * @param User $author
     *
     * @return Comment
     */
    public function setAuthor(User $author): Comment {
        $this->author = $author;
        return $this;
    }


    /**
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Comment
     */
    public function setContent(string $content): Comment {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTargetContent() {
        return $this->targetContent;
    }

    /**
     * @param mixed $targetContent
     * @return Comment
     */
    public function setTargetContent($targetContent): Comment {
        $this->targetContent = $targetContent;
        return $this;
    }

    public function jsonSerialize(): array {
        return [
            'id' => $this->getId(),
            'author' => $this->getAuthor()->jsonSerialize(),
            'content' => $this->getContent()
        ];
    }

    public function detailJsonSerialize(bool $withAccess = false): array {
        return array_merge($this->jsonSerialize(), [
            'targetContent' => $this->getTargetContent()
        ]);
    }
}