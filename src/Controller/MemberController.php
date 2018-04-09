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
use App\Repository\MemberRepository;
use App\Services\Paginate\Member as PaginateMember;
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as RestView;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\ConstraintViolationList;


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
     * @Rest\Get("/api/customer/{idCustomer}/members")
     *
     * @SWG\Response(
     *     response="200",
     *     description="Returned when successful",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Member::class, groups={"Default"} ))
     *     )
     * )
     *
     * @param Customer              $customer
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
     * @ParamConverter("customer", options={"id"="idCustomer"})
     *
     * @Rest\View(serializerGroups={"Default"})
     *
     * @throws \LogicException
     *
     * @return PaginateMember
     */
    public function cgetAction(Customer $customer, ParamFetcherInterface $paramFetcher)
    {
        /** @var Pagerfanta $pager */
        $pager = $this->_getRepository()->findByCustomerWithPaginate(
            $customer,
            $paramFetcher->get('max_per_page'),
            $paramFetcher->get('current_page')
        );

        return new PaginateMember($pager);
    }

    /**
     * View the details of a member
     *
     * @Rest\Get("/api/members/{idMember}")
     *
     * @param $idMember
     *
     * @SWG\Response(
     *     response="200",
     *     description="Returned when successful",
     *     @Model(type=Member::class, groups={"Default", "Details"} )
     * )
     *
     * @Rest\View(serializerGroups={"Default", "Details"})
     *
     * @throws \LogicException
     *
     * @return Member|null|object
     */
    public function getAction($idMember)
    {
        return $this->_getRepository()->find($idMember);
    }

    /**
     * Create a new member link to customer
     *
     * @Rest\Post("/api/customer/{idCustomer}/members")
     *
     * @param  Customer                $customer
     * @param  ConstraintViolationList $validationError
     *
     * @ParamConverter("customer", options={"id"="idCustomer"})
     *
     * @SWG\Parameter(
     *     in="formData",
     *     name="member",
     *     type="array",
     *     @Model(type=MemberType::class)
     * )
     * @SWG\Response(
     *     response="201",
     *     description="Create successfully",
     *       @Model(type=Member::class, groups={"Default"} )
     * )
     *
     * @Rest\View(serializerGroups={"Default"})
     *
     * TODO to finish
     */
    public function newAction(Request $request, Customer $customer)
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $member->setCustomer($customer);
            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();

            return RestView::create(
                ['resource' => $member],
                Response::HTTP_CREATED
            );
        } else {
            return RestView::create(
                [],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws \LogicException
     *
     * @return MemberRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private function _getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Member::class);
    }
}
