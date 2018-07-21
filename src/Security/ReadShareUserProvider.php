<?php

namespace ReadShare\Security;

use Doctrine\ORM\EntityManagerInterface;
use ReadShare\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ReadShareUserProvider implements UserProviderInterface {
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $ementityManager) {
        $this->entityManager = $ementityManager;
    }

    public function loadUserByUsername($username) {
        /** @var User $user */
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneByUsername($username);

        if($user)
            return $user;
        else
            throw new UsernameNotFoundException('Error username or password');
    }

    public function refreshUser(UserInterface $user) {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class) {
        return User::class == $class;
    }

}