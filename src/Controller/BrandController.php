<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use App\Services\Paginate\Brand as PaginateBrand;
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as RestView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use App\Services\ViewErrorsHelper;
use Symfony\Component\Translation\TranslatorInterface;

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
     * @Rest\Get(
     *     path="/api/brands",
     *     name="app_api_brand_cget"
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
     * @Rest\View(serializerGroups={"Default"})
     *
     * @throws \LogicException
     *
     * @return PaginateBrand
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher)
    {
        $pager = $this->_getRepository()->findAllWithPaginate(
            $paramFetcher->get('max_per_page'),
            $paramFetcher->get('current_page')
        );

        return new PaginateBrand($pager);
    }

    /**
     * View the details of the brand
     *
     * @Rest\Get(
     *     path="/api/brands/{idBrand}",
     *     name="app_api_brand_get",
     *     requirements={"idBrand"="\d+"}
     * )
     *
     * @param Brand $brand
     * @ParamConverter("brand", options={"id"="idBrand"} )
     *
     * @SWG\Response(
     *     response="200",
     *     description="Returned when successful",
     *     @Model(type=Brand::class, groups={"Default", "Details"} )
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Returned when the brand is not found"
     * )
     *
     * @Rest\View(serializerGroups={"Default", "Details"})
     *
     * @throws \LogicException
     *
     * @return Brand|RestView
     */
    public function getAction(Brand $brand)
    {
        return $brand;
    }

    /**
     * Create a new brand
     *
     * @Rest\Post("/api/brands")

     * @SWG\Response(
     *     response="201",
     *     description="Create successfully",
     *       @Model(type=Brand::class, groups={"Default"} )
     * )
     *
     * @Rest\View(serializerGroups={"Default"})
     */
    public function newAction(Request $request)
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($brand);
            $em->flush();

            return RestView::create(
                ['resource' => $brand],
                Response::HTTP_CREATED
            );
        } else {
            /** @var TranslatorInterface $translalor */
            $translator = $this->container->get('translator');

            return RestView::create(
                ['errors' => (new ViewErrorsHelper($translator))->getErrors($form)],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Complete change of brand data
     *
     * @Rest\Put(
     *     path="/api/brands/{idBrand}",
     *     name="app_api_brand_put",
     *     requirements={"idBrand"="\d+"}
     * )
     *
     * @param Request $request
     * @param Brand   $brand
     * @ParamConverter("brand", options={"id"="idBrand"} )
     *
     * @SWG\Response(
     *     response="200",
     *     description="Returned when successful",
     *     @Model(type=Brand::class, groups={"Default", "Details"} )
     * )
     *
     * @SWG\Response(
     *     response="400",
     *     description="Returned when submitted data is invalid"
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Returned when the brand is not found"
     * )
     */
    public function putAction(Request $request, Brand $brand)
    {
        return $this->updateResource($request, $brand, true);

    }

    /**
     * Partial change of brand data
     *
     * @Rest\Patch(
     *     path="/api/brands/{idBrand}",
     *     name="app_api_brand_patch",
     *     requirements={"idBrand"="\d+"}
     * )
     *
     * @param Request $request
     * @param Brand   $brand
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
        return $this->updateResource($request, $brand, false);

    }

    /**
     * Delete a brand
     *
     * @Rest\Delete(
     *     path="/api/brands/{idBrand}",
     *     name="app_api_brand_delete",
     *     requirements={"idBrand"="\d+"}
     * )
     *
     * @param $idBrand
     *
     * @SWG\Response(
     *     response="204",
     *     description="Response no content"
     *     )
     *
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     */
    public function removeAction($idBrand)
    {
        $em = $this->getDoctrine()->getManager();
        $resource = $this->_getRepository()->find($idBrand);

        if ($resource) {
            $em->remove($resource);
            $em->flush();
        }
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     * @param bool    $clearMissing
     */
    protected function updateResource(Request $request, Brand $brand, bool $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(BrandType::class, $brand);
        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            $em->persist($brand);
            $em->flush();

            return RestView::create(
                $brand,
                Response::HTTP_CREATED
            );
        } else {
            /** @var TranslatorInterface $translalor */
            $translator = $this->container->get('translator');

            return RestView::create(
                ['errors' => (new ViewErrorsHelper($translator))->getErrors($form)],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws \LogicException
     *
     * @return BrandRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private function _getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Brand::class);
    }


}
