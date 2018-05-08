<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Controller;

use App\Entity\Brand;
use App\Manager\BrandManager;
use App\Services\Paginate\Brand as PaginateBrand;
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as RestView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationList;
use Swagger\Annotations as SWG;

/**
 * Class BrandController
 *
 * @package App\Controller
 */
class BrandController extends Controller
{
    /**
     * Consult the list of the brand
     *
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Get(
     *     path="/api/brands",
     *     name="app_api_brand_cget"
     * )
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param BrandManager          $brandManager
     *
     * @QueryParam (
     *     name="page",
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
     * @SWG\Response(
     *     response="200",
     *     description="Returned when successful",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Brand::class, groups={"Default"} ))
     *     )
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Returned when there is no result for the submitted parameters"
     * )
     *
     * @Rest\View(serializerGroups={"Default"})
     *
     * @return PaginateBrand
     *
     * @throws \LogicException
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, BrandManager $brandManager)
    {
        $pagerfanta = $brandManager->findAllWithPaginate(
            $paramFetcher->get('max_per_page'),
            $paramFetcher->get('page')
        );

        return new PaginateBrand($pagerfanta);
    }

    /**
     * View the details of the brand
     *
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Get(
     *     path="/api/brands/{idBrand}",
     *     name="app_api_brand_get",
     *     requirements={"idBrand"="\d+"}
     * )
     *
     * @param int          $idBrand
     * @param BrandManager $brandManager
     *
     * @return Brand|null
     *
     * @throws NotFoundHttpException
     * @SWG\Response(
     *     response="200",
     *     description="Returned when successful",
     *     @Model(type=Brand::class, groups={"Default"} )
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Returned when the brand is not found"
     * )
     *
     * @Rest\View(serializerGroups={"Default"})
     *
     */
    public function getAction(int $idBrand, BrandManager $brandManager)
    {
        $brand = $brandManager->find($idBrand);
        if (empty($brand)) {
            throw new NotFoundHttpException('Unknown identifier');
        }

        return $brand;
    }

    /**
     * Create a new brand
     *
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Post("/api/brands")
     *
     * @param Brand                   $brand
     * @param BrandManager            $brandManager
     * @param ConstraintViolationList $violationList
     *
     * @ParamConverter("brand", converter="fos_rest.request_body")
     *
     * @SWG\Response(
     *     response="201",
     *     description="Create successfully",
     *     @Model(type=Brand::class, groups={"Default"} )
     * )
     *
     * @Rest\View(serializerGroups={"Default"})
     *
     * @return RestView
     */
    public function newAction(
        Brand $brand,
        BrandManager $brandManager,
        ConstraintViolationList $violationList
    ): RestView {
        if (count($violationList)) {
            return RestView::create(['errors' => $violationList], Response::HTTP_BAD_REQUEST);
        }

        $brandManager->add($brand);

        return RestView::create(['resource' => $brand], Response::HTTP_CREATED);
    }

    /**
     * @TODO à revoir
     *
     * Complete change of brand data
     *
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Put(
     *     path="/api/brands/{idBrand}",
     *     name="app_api_brand_put",
     *     requirements={"idBrand"="\d+"}
     * )
     *
     * @param Brand                   $brand
     * @param BrandManager            $brandManager
     * @param ConstraintViolationList $violationList
     *
     * @return RestView
     * @ParamConverter("brand", options={"id"="idBrand"} )
     * --ParamConverter("brand", converter="fos_rest.request_body")*
     *
     * @SWG\Response(
     *     response="200",
     *     description="Returned when successful",
     *     @Model(type=Brand::class, groups={"Default", "Details"} )
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Returned when submitted data is invalid"
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Returned when the brand is not found"
     * )
     *
     */
    public function putAction(
        Brand $brand,
        BrandManager $brandManager,
        ConstraintViolationList $violationList
    ): RestView {
        if (count($violationList)) {
            return RestView::create(['errors' => $violationList], Response::HTTP_BAD_REQUEST);
        }

        $brandManager->add($brand);

        return RestView::create(['resource' => $brand], Response::HTTP_CREATED);
    }

    /**
     * TODO à Revoir
     *
     * Partial change of brand data
     *
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Patch(
     *     path="/api/brands/{idBrand}",
     *     name="app_api_brand_patch",
     *     requirements={"idBrand"="\d+"}
     * )
     *
     * @param Request $request
     * @param Brand   $brand
     *
     * @ParamConverter("brand", options={"id"="idBrand"} )
     *
     * @SWG\Response(
     *     response="200",
     *     description="Returned when successful",
     *     @Model(type=Brand::class, groups={"Default", "Details"} )
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Returned when submitted data is invalid"
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Returned when the brand is not found"
     * )
     */
    public function patchAction(Request $request, Brand $brand)
    {
        //return $this->updateResource($request, $brand, false);

    }

    /**
     * Delete a brand
     *
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Delete(
     *     path="/api/brands/{idBrand}",
     *     name="app_api_brand_delete",
     *     requirements={"idBrand"="\d+"}
     * )
     *
     * @param int          $idBrand
     * @param BrandManager $brandManager
     *
     * @throws NotFoundHttpException
     * @SWG\Response(
     *     response="204",
     *     description="Response no content when delete to make"
     *     )
     * @SWG\Response(
     *     response="404",
     *     description="Returned when the brand is not found"
     * )
     * @SWG\Response(
     *     response="500",
     *     description="Returned when exist foreign key constraint violation"
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     */
    public function removeAction(int $idBrand, BrandManager $brandManager)
    {
        $brand = $brandManager->find($idBrand);

        if (empty($brand)) {
            throw new NotFoundHttpException('Unknown identifier');
        }

        $brandManager->remove($idBrand);
    }
}
