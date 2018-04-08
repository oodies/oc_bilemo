<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Services\Paginate\Product as PaginateProduct;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ProductController
 *
 * @package App\Controller
 */
class ProductController extends Controller
{
    /**
     * Consult the list of products
     *
     * @Rest\Get("/products")
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @QueryParam (
     *     name="current_page",
     *     requirements="\d+",
     *     default="1",
     *     description="Pagination start index"
     * )
     *
     * @QueryParam (
     *     name="max_per_page",
     *     requirements="\d+",
     *     default="10",
     *     description="Maximum number of results from index"
     * )
     *
     * @throws \LogicException
     *
     * @return PaginateProduct
     *
     * @Rest\View(serializerGroups={"Default"})
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher)
    {
        $pager = $this->_getRepository()->findAllWithPaginate(
            $paramFetcher->get('max_per_page'),
            $paramFetcher->get('current_page')
        );

        return new PaginateProduct($pager);
    }

    /**
     * View the details of the product
     *
     * @Rest\Get("/products/{idProduct}")
     *
     * @Rest\View(serializerGroups={"Default", "Details"})
     */
    public function getAction($idProduct)
    {
        return $this->_getRepository()->find($idProduct);
    }

    /**
     *
     * @throws \LogicException
     *
     * @return ProductRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private function _getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Product::class);
    }
}
