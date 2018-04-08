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
use App\Repository\MemberRepository;
use App\Services\Paginate\Member as PaginateMember;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @Rest\Get("/customer/{idCustomer}/members")
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
     * @Rest\Get("/members/{idMember}")
     *
     * @Rest\View(serializerGroups={"Default", "Details"})
     */
    public function getAction($idMember)
    {
        return $this->_getRepository()->find($idMember);
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
