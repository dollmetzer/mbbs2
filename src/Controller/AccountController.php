<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/account/login", name="account_login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/account/logout", name="account_logout")
     */
    public function logoutAction(): void
    {
        // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/account/register", name="account_register")
     */
    public function registerAction(): void
    {
        die('Not implemented yet');
    }

    /**
     * @Route("/account/confirm", name="account_confirm")
     */
    public function confirmAction(): void
    {
        die('Not implemented yet');
    }

    /**
     * @Route("/account/impersonate", name="account_impersonate")
     */
    public function impersonateAction(): void
    {
        die('Not implemented yet');
    }
}