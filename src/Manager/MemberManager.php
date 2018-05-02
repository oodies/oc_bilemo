<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Manager;

use App\Entity\Customer;
use App\Entity\Member;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Pagerfanta;

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

    /** *******************************
     *  METHODS
     */

    /**
     * MemberManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param MemberRepository       $memberRepository
     */
    public function __construct(EntityManagerInterface $em, MemberRepository $memberRepository)
    {
        $this->em = $em;
        $this->repository = $memberRepository;
    }

    /**
     *
     * @param Customer $customer
     * @param          $mawPerPage
     * @param          $currentPage
     *
     * @return \Pagerfanta\Pagerfanta
     *
     * @throws \LogicException
     */
    public function findByCustomerWithPaginate(Customer $customer, $mawPerPage, $currentPage): Pagerfanta
    {
        return $this->repository->findByCustomerWithPaginate($customer, $mawPerPage, $currentPage);
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
     * @param int $idMember
     */
    public function remove(int $idMember)
    {
        $member = $this->find($idMember);

        if ($member) {
            $this->em->remove($member);
            $this->em->flush();
        }
    }
}
