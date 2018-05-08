<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Repository;

use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class UserRepository
 *
 * @package App\Repository
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Loads the user for the given username.
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @throws \Doctrine\ORM\NonUniqueResultException*
     *
     * @return User|null
     */
    public function loadUserByUsername($username): ?User
    {
        return $this->createQueryBuilder('u')
                    ->where('u.username = :username OR u.email = :email')
                    ->setParameter('username', $username)
                    ->setParameter('email', $username)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * @param $apiKey
     *
     * @return User|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByApiKey($apiKey): ?User
    {
        return $this->createQueryBuilder('u')
                    ->where('u.apiKey = :apiKey')
                    ->setParameter('apiKey', $apiKey)
                    ->getQuery()
                    ->getOneOrNullResult();
    }
}
