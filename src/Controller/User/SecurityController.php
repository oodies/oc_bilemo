<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Controller\User;

use App\Form\User\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 *
 * @package App\Controller
 */
class SecurityController extends Controller
{
    /**
     * @param AuthenticationUtils $authUtils
     *
     * @Route("/login")
     *
     * @return RedirectResponse|Response
     * @throws \LogicException
     */
    public function loginAction(AuthenticationUtils $authUtils): Response
    {
        if (!is_null($this->getUser())) {
            return $this->redirectToRoute('app.swagger_ui');
        }

        // last username entered by the user
        $form = $this->createForm(
            LoginForm::class,
            ['_username' => $authUtils->getLastUsername()]
        );

        return $this->render(
            'User/Security/login.html.twig',
            [
                'form'  => $form->createView(),
                'error' => $authUtils->getLastAuthenticationError()
            ]
        );
    }

    /**
     * @Route("/logout")
     */
    public function logoutAction()
    {
    }
}
