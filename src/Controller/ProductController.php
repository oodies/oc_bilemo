<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Manager\ProductManager;
use App\Services\Paginate\Product as PaginateProduct;
use App\Services\ViewErrorsHelper;
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as RestView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\TranslatorInterface as Translator;
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
     *     path="/api/products/{idProduct}",
     *     name="app_api_product_get",
     *     requirements={"idProduct"="\d+"}
     * )
     *
     * @param int            $idProduct
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
    public function getAction(int $idProduct, ProductManager $productManager)
    {
        $product = $productManager->find($idProduct);
        if (empty($product)) {
            throw new NotFoundHttpException('Unknown identifier');
        }

        return $product;
    }

    /**
     * Create a new product
     *
     * TODO à revoir
     *
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Post("/api/products")
     *
     * @param Request        $request
     * @param ProductManager $productManager
     * @param Translator     $translator
     *
     * @SWG\Parameter(
     *     in="body",
     *     name="product",
     *     @SWG\Schema(
     *          ref=@Model(type=ProductType::class, groups={"Default", "Details"} )
     *      )
     * )
     * @SWG\Response(
     *     response="201",
     *     description="Create successfully",
     *     @Model(type=Product::class, groups={"Default", "Details"} )
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Returned when submitted data is invalid"
     * )
     *
     * @Rest\View(serializerGroups={"Default", "Details"})
     *
     * @return RestView
     *
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws \LogicException
     */
    public function newAction(
        Request $request,
        ProductManager $productManager,
        Translator $translator
    ): RestView {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $data = $this->get('jms_serializer')->deserialize($request->getContent(), 'array', 'json');

        $form->submit($data);
        if ($form->isValid()) {
            $productManager->add($product);
            return RestView::create(['resource' => $product], Response::HTTP_CREATED);
        }

        return RestView::create(
            ['errors' => (new ViewErrorsHelper($translator))->getErrors($form)],
            Response::HTTP_BAD_REQUEST
        );

    }

    /**
     * Delete a product
     *
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Delete(
     *      path="/api/products/{idProduct}",
     *      name="app_api_product_delete",
     *      requirements={"idProduct"="\d+"}
     * )
     *
     * @param int            $idProduct
     * @param ProductManager $productManager
     *
     * @SWG\Response(
     *     response="204",
     *     description="Response no content when delete to make"
     *     )
     * @SWG\Response(
     *     response="404",
     *     description="Returned when the product is not found"
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     *
     * @throws NotFoundHttpException
     */
    public function removeAction(int $idProduct, ProductManager $productManager)
    {
        $product = $productManager->find($idProduct);

        if (empty($product)) {
            throw new NotFoundHttpException('Unknown identifier');
        }

        $productManager->remove($idProduct);
    }
}
