<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Repository;

use App\Entity\Brand;
use App\Services\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Brand|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brand|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brand[]    findAll()
 * @method Brand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrandRepository extends ServiceEntityRepository
{
    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * BrandRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry, Paginator $paginator)
    {
        $this->paginator = $paginator;

        parent::__construct($registry, Brand::class);
    }

    /**
     * @param int|null $maxPerPage
     * @param int|null $currentPage
     *
     * @throws \LogicException
     *
     * @return \Pagerfanta\Pagerfanta
     */
    public function findAllWithPaginate($maxPerPage = null, $currentPage = null)
    {
        $qb = $this->createQueryBuilder('B');
        return $this->paginator->paginate($qb, $maxPerPage, $currentPage);
    }
}
