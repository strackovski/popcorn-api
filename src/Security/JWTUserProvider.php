<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class JWTUserProvider
 *
 * @package      App\Security
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class JWTUserProvider implements UserProviderInterface
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Load user by their username or email
     *
     * @param string $userId
     *
     * @return null|object|UserInterface
     */
    public function loadUserByUsername($userId)
    {
        return $this->repository->findUser($userId);

        // Disable db check
//         $u = new User();
//         $u->setUsername($userId);
//         $u->addRole('ROLE_USER');
//         $u->setEnabled(true);
//
//         return $u;
    }

    /**
     * Refresh user - this is disabled in stateless APIs
     *
     * @param UserInterface $user
     *
     * @return UserInterface|void
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException("The user given is not supported.");
    }

    /**
     * Return the supported user class
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
