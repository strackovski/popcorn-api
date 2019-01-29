<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class RefreshTokenUserProvider
 *
 * @package      App\Security
 * @author       Vladimir Strackovski <vladimir.strackovski@nv3.eu>
 * @copyright    2019 nv3 (https://www.nv3.eu)
 */
class RefreshTokenUserProvider implements UserProviderInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Load user by their username or email
     *
     * @param string $username The user ID
     *
     * @return null|object|UserInterface
     */
    public function loadUserByUsername($username)
    {
        if (($user = $this->em->getRepository('App:User')->findOneBy(['username' => $username])) !== null) {
            return $user;
        }

        if (($user = $this->em->getRepository('App:User')->findOneBy(['email' => $username])) !== null) {
            return $user;
        }

        throw new UsernameNotFoundException(sprintf('No user with username or email %s', $username));
    }

    /**
     * Refresh user - throws exception as this is stateless authentication
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
