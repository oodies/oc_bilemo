<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Member;
use App\Form\MemberType;
use App\Manager\MemberManager;
use App\Services\Paginate\Member as PaginateMember;
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as RestView;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationList;
use Swagger\Annotations as SWG;

/**
 * Class MemberController
 *
 * @package App\Controller
 */
class MemberController extends Controller
{
    /**
     * Consult the list of registered members linked to a customer
     *
     * @Rest\Get(
     *     path="/api/customer/{idCustomer}/members",
     *     name="app_api_member_cget",
     *     requirements={"idCustomer"="\d+"}
     * )
     *
     * @param Customer              $customer
     * @param ParamFetcherInterface $paramFetcher
     * @param MemberManager         $memberManager
     *
     * @ParamConverter("customer", options={"id"="idCustomer"})
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
     *          @SWG\Items(ref=@Model(type=Member::class, groups={"Default"} ))
     *     )
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Returned when there is no result for the submitted parameters"
     * )
     *
     * @Rest\View(serializerGroups={"Default"})
     *
     * @return PaginateMember
     *
     * @throws \LogicException
     *
     */
    public function cgetAction(Customer $customer, ParamFetcherInterface $paramFetcher, MemberManager $memberManager)
    {
        /** @var Pagerfanta $pager */
        $pagerfanta = $memberManager->findByCustomerWithPaginate(
            $customer,
            $paramFetcher->get('max_per_page'),
            $paramFetcher->get('page')
        );

        return new PaginateMember($pagerfanta);
    }

    /**
     * View the details of a member
     *
     * @Rest\Get(
     *     path="/api/members/{idPerson}",
     *     name="app_api_member_get",
     *     requirements={"idPerson"="\d+" }
     * )
     *
     * @param Member $member
     *
     * @ParamConverter("member", options={"id": "idPerson"} )
     *
     * @SWG\Response(
     *     response="200",
     *     description="Returned when successful",
     *     @Model(type=Member::class, groups={"Default", "Details"} )
     * )
     *
     * @Rest\View(serializerGroups={"Default", "Details"})
     *
     * @return Member|null
     *
     * @throws NotFoundHttpException
     */
    public function getAction(int $idPerson, MemberManager $memberManager)
    {
        $member = $memberManager->find($idPerson);
        if (empty($member)) {
            throw new NotFoundHttpException('Unknown indentifier');
        }

        return $member;
    }

    /**
     * Create a new member link to customer
     *
     * @Rest\Post("/api/customer/{idCustomer}/members")
     *
     * @param Member                  $member
     * @param Customer                $customer
     * @param MemberManager           $memberManager
     * @param ConstraintViolationList $violationList
     *
     * @ParamConverter("customer", options={"id"="idCustomer"})
     * @ParamConverter("member", converter="fos_rest.request_body")
     *
     * @SWG\Parameter(
     *     in="formData",
     *     name="member",
     *     type="array",
     *     @Model(type=MemberType::class, groups={"Default"} )
     * )
     * @SWG\Response(
     *     response="201",
     *     description="Create successfully",
     *     @Model(type=Member::class, groups={"Default"} )
     * )
     *
     * @Rest\View(serializerGroups={"Default"})
     *
     * @return RestView
     */
    public function newAction(
        Member $member,
        Customer $customer,
        MemberManager $memberManager,
        ConstraintViolationList $violationList
    ): RestView {

        $member->setCustomer($customer);

        if (count($violationList)) {
            return RestView::create(['errors' => $violationList], Response::HTTP_BAD_REQUEST);
        }

        $memberManager->add($member);

        return RestView::create(['resource' => $member], Response::HTTP_CREATED);
    }

    /**
     * TODO à revoir
     *
     * Partial change of member data
     *
     * @Rest\Patch(
     *     path="/api/members/{idPerson}",
     *     name="app_api_member_patch",
     *     requirements={"idMember"="\d+"}
     * )
     *
     * @param Member                  $member
     * @param MemberManager           $memberManager
     * @param ConstraintViolationList $violationList
     *
     * @ParamConverter("member", options={"id"="idPerson"} )
     *
     * @return RestView
     */
    public function patchAction(
        Member $member,
        MemberManager $memberManager,
        ConstraintViolationList $violationList
    ): RestView {
        if (count($violationList)) {
            return RestView::create(['errors' => $violationList], Response::HTTP_BAD_REQUEST);
        }

        $memberManager->add($member);

        return RestView::create(['resource' => $member], Response::HTTP_CREATED);
    }

    /**
     * Delete a member
     *
     * @Rest\Delete(
     *     path="/api/members/{idPerson}",
     *     name="app_api_member_delete",
     *     requirements={"idPerson"="\d+"}
     * )
     *
     * @param int           $idPerson
     * @param MemberManager $memberManager
     *
     * @SWG\Response(
     *     response="204",
     *     description="Response no content when delete to make"
     *     )
     * @SWG\Response(
     *     response="404",
     *     description="Returned when the member is not found"
     * )
     * @SWG\Response(
     *     response="500",
     *     description="Returned when exist foreign key constraint violation"
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     *
     * @throws NotFoundHttpException
     */
    public function removeAction(int $idPerson, MemberManager $memberManager)
    {
        $member = $memberManager->find($idPerson);

        if (empty($member)) {
            throw new NotFoundHttpException('Unknown identifier');
        }

        $memberManager->remove($idPerson);
    }
}
