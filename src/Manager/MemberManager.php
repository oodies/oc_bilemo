<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Manager;

use App\Entity\Member;
use App\Entity\User\User;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class MemberManager
 *
 * @package App\Manager
 */
class MemberManager
{
    /** *******************************
     *  PROPERTIES
     */

    /**
     * @var MemberRepository
     */
    protected $repository;

    /**
     * @var EntityManagerInterface $em
     */
    protected $em;

    /**
     * @var TokenStorageInterface $tokenStorage
     */
    protected $tokenStorage;

    /** *******************************
     *  METHODS
     */

    /**
     * MemberManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param MemberRepository       $memberRepository
     * @param TokenStorageInterface  $tokenStorage
     */
    public function __construct(
        EntityManagerInterface $em,
        MemberRepository $memberRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->em = $em;
        $this->repository = $memberRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param int $id
     *
     * @return Member|null
     */
    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param int $id
     *
     * @return Member|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByCustomer(int $id)
    {

        return $this->repository->findOneByCustomer($id, $this->getUser()->getCustomer());
    }

    /**
     *
     * @param          $mawPerPage
     * @param          $currentPage
     *
     * @return \Pagerfanta\Pagerfanta
     *
     * @throws \LogicException
     */
    public function findByCustomerWithPaginate($mawPerPage, $currentPage): Pagerfanta
    {
        return $this->repository->findByCustomerWithPaginate(
            $this->getUser()->getCustomer(), $mawPerPage, $currentPage
        );
    }

    /**
     * Add a member
     *
     * @param Member $member
     */
    public function add(Member $member)
    {
        $this->em->persist($member);
        $this->em->flush();
    }

    /**
     * Remove a member
     *
     * @param int $id
     */
    public function remove(int $id)
    {
        $member = $this->find($id);

        if ($member) {
            $this->em->remove($member);
            $this->em->flush();
        }
    }

    /**
     * @return User
     */
    protected function getUser(): User
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}
