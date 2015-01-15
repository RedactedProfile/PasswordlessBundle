<?php

namespace Redacted\PasswordlessBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AccountRepository extends EntityRepository implements UserProviderInterface
{
    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        $q = $this->createQueryBuilder('u')
                  ->where('u.email = :email OR u.uid = :uid')
                  ->setParameter('email', $username)
                  ->setParameter('uid', $username)
                ->getQuery();

        try {
            $user = $q->getSingleResult();
        } catch(NoResultException $e) {
            $message = sprintf('Unable to find user with email: %s', $username);
            throw new UsernameNotFoundException($message, 0, $e);
        }

        return $user;
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if(!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of %s are not supported', $class));
        }

        return $this->find($user->getId());
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
                || is_subclass_of($class, $this->getEntityName());
    }

}