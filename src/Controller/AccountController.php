<?php
/**
 * M B B S 2   -   B u l l e t i n   B o a r d   S y s t e m
 * ---------------------------------------------------------
 * A small BBS package for mobile use
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2014-2020, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class AccountController
 *
 * @package App\Controller
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/account/login", name="account_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
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
    public function impersonateformAction(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ALLOWED_TO_SWITCH');

        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();

        return $this->render('security/impersonate.html.twig', ['users' => $users]);
    }
}
