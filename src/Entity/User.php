<?php

namespace ReadShare\Entity;

use ReadShare\Library\Frontend\FrontendSerializableInterface;
use \Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements UserInterface, FrontendSerializableInterface {
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string[]
     *
     * @ORM\Column(type="simple_array")
     */
    private $roles = ['ROLE_USER'];

    /**
     * @return int
     */
    public function getUid(): ? int {
        return $this->uid;
    }

    /**
     * @param int $uid
     * @return User
     */
    public function setUid(int $uid): User {
        $this->uid = $uid;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): ? string {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ? string {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ? string {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles): User {
        $this->roles = $roles;
        return $this;
    }

    public function getSalt() {
        return null;
    }

    public function eraseCredentials() {
    }

    public function jsonSerialize(): array {
        return [
            'uid' => $this->getUid(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'role' => $this->getRoles()
        ];
    }

    public function detailJsonSerialize(bool $withAccess = false): array {
        $obj = array_merge($this->jsonSerialize(), []);

        if($withAccess) {
            $obj = array_merge($obj, []);
        }

        return $obj;
    }
}