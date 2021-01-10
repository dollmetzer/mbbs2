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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AccountController
 *
 * @package App\Controller
 */
class AccountController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        TranslatorInterface $translator,
        SessionInterface $session
    )
    {
        $this->translator = $translator;
        $this->passwordEncoder = $passwordEncoder;
        $this->session = $session;
    }

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
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request): Response
    {
        if (true !== $this->getParameter('selfregister')) {
            return $this->redirectToRoute('index_index');
        }

        $locales = $this->getParameter('locales');
        $handle = '';
        $locale = $locales[0];

        if (!empty($_POST)) {
            $success = true;
            $handle = strip_tags($_POST['admin_user']['handle']);
            if ((strlen($handle) < 4) or (strlen($handle) > 32)) {
                $success = false;
                $this->addFlash('error', $this->translator->trans('base.message.handlelength'));
            }

            $repository = $this->getDoctrine()->getManager()->getRepository(User::class);
            $user = $repository->findBy(['handle' => $handle]);
            if($user) {
                $success = false;
                $this->addFlash('error', $this->translator->trans('base.message.handleexists'));
            }

            $password = $_POST['admin_user']['password'];
            if ($password !== $_POST['admin_user']['password2']) {
                $success = false;
                $this->addFlash('error', $this->translator->trans('base.message.differentpasswords'));
            }

            $locale = $_POST['admin_user']['locale'];
            if (!in_array($locale, $locales)) {
                $success = false;
                $this->addFlash('error', $this->translator->trans('base.message.unsupportedlanguage'));
            }

            if ($success === true) {
                $user = new User();
                $user->setHandle($handle);
                $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
                $user->setLocale($locale);
                $user->setIsActive(true);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('notice', $this->translator->trans('base.message.accountcreated'));

                return $this->redirectToRoute('account_login');
            }
        }

        return $this->render('security/register.html.twig', [
            'handle' => $handle,
            'locales' => $locales,
            'locale' => $locale
        ]);
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
