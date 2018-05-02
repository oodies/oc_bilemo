<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace App\Manager;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class BrandManager
 *
 * @package App\Manager
 */
class BrandManager
{
    /** *******************************
     *  PROPERTIES
     */

    /**
     * @var BrandRepository
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
     * BrandManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param BrandRepository        $brandRepository
     */
    public function __construct(EntityManagerInterface $em, BrandRepository $brandRepository)
    {
        $this->em = $em;
        $this->repository = $brandRepository;
    }

    /**
     * @param int $idBrand
     *
     * @return Brand|null
     */
    public function find(int $idBrand)
    {
        return $this->repository->find($idBrand);
    }

    /**
     * @param $maxPerPAge
     * @param $currentPage
     *
     * @return \Pagerfanta\Pagerfanta
     *
     * @throws \LogicException
     */
    public function findAllWithPaginate($maxPerPAge, $currentPage)
    {
        return $this->repository->findAllWithPaginate($maxPerPAge, $currentPage);
    }

    /**
     * Add a brand
     *
     * @param Brand $brand
     */
    public function add(Brand $brand)
    {
        $this->em->persist($brand);
        $this->em->flush();
    }

    /**
     * Remove a brand
     *
     * @param int $idBrand
     */
    public function remove(int $idBrand)
    {
        $brand = $this->find($idBrand);

        if ($brand) {
            $this->em->remove($brand);
            $this->em->flush();
        }
    }
}
