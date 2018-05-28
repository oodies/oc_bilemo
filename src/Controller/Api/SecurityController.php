<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace App\Controller\Api;

use App\Manager\UserManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Swagger\Annotations as SWG;

/**
 * Class SecurityController
 *
 * @package App\Controller\Api
 */
class SecurityController extends Controller
{
    /**
     * @Rest\Post(
     *     path="/api/login",
     *     name="app_api_security_login"
     *     )
     *
     * @QueryParam(
     *     name="username",
     *     requirements="\w+",
     *     description="Your username"
     * )
     * @QueryParam(
     *     name="password",
     *     requirements="\w+",
     *     description="A password"
     * )
     *
     * @SWG\Response(
     *     response="200",
     *     description="Returned the token"
     * )
     * @SWG\Response(
     *     response="403",
     *     description="Returned when there is bad credentials"
     * )
     *
     * @param JWTTokenManagerInterface     $JWTTokenManager
     * @param ParamFetcherInterface        $paramFetcher
     * @param UserManager                  $userManager
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     *
     * @return JsonResponse
     *
     * @throws AccessDeniedHttpException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function apiLoginAction(
        JWTTokenManagerInterface $JWTTokenManager,
        ParamFetcherInterface $paramFetcher,
        UserManager $userManager,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $username = $paramFetcher->get('username');
        $user = $userManager->loadUserByUsername($username);

        if (null !== $user) {
            if ($userPasswordEncoder->isPasswordValid($user, $paramFetcher->get('password'))) {
                return new JsonResponse(['token' => $JWTTokenManager->create($user)]);
            }
        }

        throw new AccessDeniedHttpException('Bad credentials');
    }
}
