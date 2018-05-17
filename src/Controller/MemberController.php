<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Manager\MemberManager;
use App\Services\Paginate\Member as PaginateMember;
use App\Services\ViewErrorsHelper;
use Nelmio\ApiDocBundle\Annotation\Model;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as RestView;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Get(
     *     path="/api/members",
     *     name="app_api_member_cget"
     * )
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param MemberManager         $memberManager
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
    public function cgetAction(
        ParamFetcherInterface $paramFetcher,
        MemberManager $memberManager
    ) {
        /** @var Pagerfanta $pager */
        $pagerfanta = $memberManager->findByCustomerWithPaginate(
            $paramFetcher->get('max_per_page'),
            $paramFetcher->get('page')
        );

        return new PaginateMember($pagerfanta);
    }

    /**
     * View the details of a member
     *
     * @Security("has_role('ROLE_API_USER')")*
     *
     * @Rest\Get(
     *     path="/api/members/{id}",
     *     name="app_api_member_get",
     *     requirements={"id"="\d+" }
     * )
     *
     * @param int           $id
     * @param MemberManager $memberManager
     *
     * @SWG\Response(
     *     response="200",
     *     description="Returned when successful",
     *     @Model(type=Member::class, groups={"Default", "Details"} )
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Returned when the member is not found"
     * )
     *
     * @Rest\View(serializerGroups={"Default", "Details"})
     *
     * @return Member|null
     *
     * @throws NotFoundHttpException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAction(
        int $id,
        MemberManager $memberManager
    ) {
        $member = $memberManager->findOneByCustomer($id);
        if (empty($member)) {
            throw new NotFoundHttpException('Unknown identifier');
        }

        return $member;
    }

    /**
     * Create a new member link to customer
     *
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Post("/api/members")
     *
     * @param Member                  $member
     * @param MemberManager           $memberManager
     * @param ConstraintViolationList $violationList
     *
     * @ParamConverter("member", converter="fos_rest.request_body")
     *
     * @SWG\Parameter(
     *     in="body",
     *     name="member",
     *     @SWG\Schema(
     *          ref=@Model(type=MemberType::class, groups={"Default", "Details"} )
     *      )
     * )
     * @SWG\Response(
     *     response="201",
     *     description="Create successfully",
     *     @Model(type=Member::class, groups={"Default", "Details"} )
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
     * @throws \LogicException
     */
    public function newAction(
        Member $member,
        MemberManager $memberManager,
        ConstraintViolationList $violationList
    ): RestView {

        $member->setCustomer($this->getUser()->getCustomer());

        if (count($violationList)) {
            return RestView::create(['errors' => $violationList], Response::HTTP_BAD_REQUEST);
        }

        $memberManager->add($member);

        return RestView::create(['resource' => $member], Response::HTTP_CREATED);
    }

    /**
     * Partial change of member data
     *
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Patch(
     *     path="/api/members/{id}",
     *     name="app_api_member_patch",
     *     requirements={"id"="\d+"}
     * )
     *
     * @param Request          $request
     * @param MemberManager    $memberManager
     * @param ViewErrorsHelper $viewErrorsHelper
     *
     * @SWG\Parameter(
     *     in="body",
     *     name="Member",
     *     @SWG\Schema(
     *          ref=@Model(type=MemberType::class, groups={"Default","Details"} )
     *      )
     * )
     * @SWG\Response(
     *     response="200",
     *     description="Returned when successful",
     *     @Model(type=Member::class, groups={"Default", "Details"} )
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Returned when submitted data is invalid"
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Returned when the member is not found"
     * )
     *
     * @return RestView
     *
     * @throws NotFoundHttpException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \LogicException
     * @throws \Symfony\Component\Form\Exception\AlreadySubmittedException
     * @throws \Symfony\Component\Form\Exception\LogicException
     */
    public function patchAction(
        Request $request,
        MemberManager $memberManager,
        ViewErrorsHelper $viewErrorsHelper
    ): RestView {
        return $this->updateResource($request, $memberManager, false, $viewErrorsHelper);
    }

    /**
     * Complete change of member data
     *
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Put(
     *     path="/api/members/{id}",
     *     name="app_api_member_put",
     *     requirements={"id"="\d+"}
     * )
     *
     * @param Request          $request
     * @param MemberManager    $memberManager
     * @param ViewErrorsHelper $viewErrorsHelper
     *
     * @SWG\Parameter(
     *     in="body",
     *     name="Member",
     *     @SWG\Schema(
     *          ref=@Model(type=MemberType::class, groups={"Default","Details"} )
     *      )
     * )
     * @SWG\Response(
     *     response="200",
     *     description="Returned when successful",
     *     @Model(type=Member::class, groups={"Default", "Details"} )
     * )
     * @SWG\Response(
     *     response="400",
     *     description="Returned when submitted data is invalid"
     * )
     * @SWG\Response(
     *     response="404",
     *     description="Returned when the member is not found"
     * )
     *
     * @return RestView
     *
     * @throws NotFoundHttpException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \LogicException
     * @throws \Symfony\Component\Form\Exception\AlreadySubmittedException
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws \Symfony\Component\Translation\Exception\InvalidArgumentException
     */
    public function putAction(
        Request $request,
        MemberManager $memberManager,
        ViewErrorsHelper $viewErrorsHelper
    ): RestView {
        return $this->updateResource($request, $memberManager, true, $viewErrorsHelper);
    }


    /**
     * Delete a member
     *
     * @Security("has_role('ROLE_API_USER')")
     *
     * @Rest\Delete(
     *     path="/api/members/{id}",
     *     name="app_api_member_delete",
     *     requirements={"id"="\d+"}
     * )
     *
     * @param int           $id
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
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws NotFoundHttpException
     */
    public function removeAction(int $id, MemberManager $memberManager)
    {
        $member = $memberManager->findOneByCustomer($id);

        if (empty($member)) {
            throw new NotFoundHttpException('Unknown identifier');
        }

        $memberManager->remove($id);
    }

    /**
     * @param Request          $request
     * @param MemberManager    $memberManager
     * @param bool             $clearMissing
     *
     * @param ViewErrorsHelper $viewErrorsHelper
     *
     * @return RestView
     *
     * @throws NotFoundHttpException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \LogicException
     * @throws \Symfony\Component\Form\Exception\AlreadySubmittedException
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws \Symfony\Component\Translation\Exception\InvalidArgumentException
     */
    private function updateResource(
        Request $request,
        MemberManager $memberManager,
        bool $clearMissing,
        ViewErrorsHelper $viewErrorsHelper
    ) {
        $member = $memberManager->findOneByCustomer($request->get('id'));
        if (empty($member)) {
            throw new NotFoundHttpException('Unknown identifier');
        }

        $form = $this->createForm(MemberType::class, $member);
        $form->submit(json_decode($request->getContent(), true), $clearMissing);

        if ($form->isValid()) {
            $memberManager->add($member);
            return RestView::create($member, Response::HTTP_CREATED);
        }

        return RestView::create(
            ['errors' => $errors = $viewErrorsHelper->getErrors($form)],
            Response::HTTP_BAD_REQUEST
        );
    }
}
