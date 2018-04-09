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
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as RestView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
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
     * @Rest\Get("/api/products")
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @QueryParam (
     *     name="current_page",
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
     * @Rest\Get("/api/products/{idProduct}")
     *
     * @param $idProduct
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
     * @throws \LogicException
     *
     * @return Product|RestView|null|object
     */
    public function getAction($idProduct)
    {
        $resource = $this->_getRepository()->find($idProduct);

        if (empty($resource)) {
            return RestView::create(
                ['message' => 'Product not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        return $resource;
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
