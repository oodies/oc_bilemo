<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\Member;
use App\Services\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class MemberRepository
 *
 * @package App\Repository
 */
class MemberRepository extends ServiceEntityRepository
{
    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * MemberRepository constructor.
     *
     * @param RegistryInterface $registry
     * @param Paginator         $paginator
     */
    public function __construct(RegistryInterface $registry, Paginator $paginator)
    {
        $this->paginator = $paginator;

        parent::__construct($registry, Member::class);
    }

    /**
     * @param Customer   $customer
     * @param int|null   $maxPerPage
     * @param int|null   $currentPage
     *
     * @throws \LogicException
     *
     * @return \Pagerfanta\Pagerfanta
     */
    public function findByCustomerWithPaginate(
        Customer $customer,
        $maxPerPage = null,
        $currentPage = null
    ): Pagerfanta
    {
        $qb = $this->createQueryBuilder('M');
        $qb->where('M.customer = :customer');
        $qb->setParameter('customer', $customer);

        return $this->paginator->paginate($qb, $maxPerPage, $currentPage);
    }
}
