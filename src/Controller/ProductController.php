<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Controller;

use App\Entity\Product;
use App\Manager\ProductManager;
use App\Services\Paginate\Product as PaginateProduct;
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Swagger\Annotations as SWG;

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
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Get(
     *     path="/api/products",
     *     name="app_api_product_cget"
     *     )
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param ProductManager        $productManager
     *
     * @QueryParam (
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="Pagination start index"
     * )
     * @QueryParam (
     *     name="max_per_page",
     *     requirements="\d+",
     *     default="10",
     *     description="Maximum number of results from index"
     * )
     *
     * @SWG\Response(
     *     response="200",
     *     description="Returned when successful",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Product::class, groups={"Default"} ))
     *     )
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Returned when there is no result for the submitted parameters"
     * )
     *
     * @Rest\View(serializerGroups={"Default"})
     *
     * @return PaginateProduct
     *
     * @throws \LogicException
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, ProductManager $productManager)
    {
        $pagerfanta = $productManager->findAllWithPaginate(
            $paramFetcher->get('max_per_page'),
            $paramFetcher->get('page')
        );

        return new PaginateProduct($pagerfanta);
    }

    /**
     * View the details of the product
     *
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Get(
     *     path="/api/products/{id}",
     *     name="app_api_product_get",
     *     requirements={"id"="\d+"}
     * )
     *
     * @param int            $id
     * @param ProductManager $productManager
     *
     * @SWG\Response(
     *     response="200",
     *     description="Returned when successful",
     *     @Model(type=Product::class, groups={"Default", "Details"} )
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Returned when the product is not found"
     * )
     *
     * @Rest\View(serializerGroups={"Default", "Details"})
     *
     * @return Product|null
     *
     * @throws NotFoundHttpException
     */
    public function getAction(int $id, ProductManager $productManager)
    {
        $product = $productManager->find($id);
        if (empty($product)) {
            throw new NotFoundHttpException('Unknown identifier');
        }

        return $product;
    }
}
