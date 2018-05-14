<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace App\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class JWTController
 *
 * @package App\Controller\Api
 */
class JWTController extends Controller
{
    /**
     * Get a new token
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @Route("/tokens", name="api_token_new")
     * @Method("GET")
     *
     * @param UserInterface $user
     *
     * @return Response
     *
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     * @throws \LogicException
     */
    public function newAction(UserInterface $user): Response
    {
        return $this->render(
            'Api/JWT/new.html.twig',
            [
                'token' => $this->get('lexik_jwt_authentication.encoder')->encode(
                    [
                        'username' => $user->getUsername(),
                        'exp'      => time() + $this->getParameter('lexik_jwt_authentication.token_ttl')
                    ]
                )
            ]
        );

    }
}
