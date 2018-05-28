<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace App\Manager;

use App\Entity\User\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserManager
 *
 * @package App\Manager
 */
class UserManager
{
    /** *******************************
     *  PROPERTIES
     */

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var EntityManagerInterface $em
     */
    protected $em;

    /** *******************************
     *  METHODS
     */

    /**
     * UserManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param UserRepository         $userRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository
    ) {
        $this->em = $em;
        $this->repository = $userRepository;
    }

    /**
     * @param $username
     *
     * @return null|User
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username): ?User
    {
        return $this->repository->loadUserByUsername($username);
    }
}
