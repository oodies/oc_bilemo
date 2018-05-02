<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace App\Manager;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Pagerfanta;

/**
 * Class ProductManager
 *
 * @package App\Manager
 */
class ProductManager
{
    /** *******************************
     *  PROPERTIES
     */

    /**
     * @var ProductRepository
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
     * ProductManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ProductRepository      $productRepository
     */
    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository)
    {
        $this->em = $em;
        $this->repository = $productRepository;
    }

    /**
     * @param int $idProduct
     *
     * @return Product|null
     */
    public function find(int $idProduct): ?Product
    {
        return $this->repository->find($idProduct);
    }

    /**
     * @param $maxPerPAge
     * @param $currentPage
     *
     * @return \Pagerfanta\Pagerfanta
     *
     * @throws \LogicException
     */
    public function findAllWithPaginate($maxPerPAge, $currentPage): Pagerfanta
    {
        return $this->repository->findAllWithPaginate($maxPerPAge, $currentPage);
    }

    /**
     * Add a product
     *
     * @param Product $product
     */
    public function add(Product $product)
    {
        $this->em->persist($product);
        $this->em->flush();
    }

    /**
     * Remove a product
     *
     * @param int $idProduct
     */
    public function remove(int $idProduct)
    {
        $product = $this->find($idProduct);

        if ($product) {
            $this->em->remove($product);
            $this->em->flush();
        }
    }
}
