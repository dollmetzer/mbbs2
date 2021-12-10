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

namespace App\Controller\Base;

use App\Domain\Base\Account;
use App\Entity\Base\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
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

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Account
     */
    private $account;

    public function __construct(
        Account $account,
        UserPasswordEncoderInterface $passwordEncoder,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        SessionInterface $session
    ) {
        $this->translator = $translator;
        $this->passwordEncoder = $passwordEncoder;
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->account = $account;
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

        return $this->render('base/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
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

        $form = $this->getAccountForm($locales, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $isOk = true;

            $password = $data['password'];
            if ($password !== $data['password2']) {
                $this->addFlash('error', $this->translator->trans('message.differentpasswords', [], 'base'));
                $isOk = false;
            }

            $repo = $this->entityManager->getRepository(User::class);
            $handle = $data['handle'];
            $user = $repo->findOneBy(['handle' => $handle]);
            if ($user) {
                $this->addFlash('error', $this->translator->trans('message.handleexists', [], 'base'));
                $isOk = false;
            }

            $locale = $data['locale'];
            if (!in_array($locale, $locales)) {
                $this->addFlash('error', $this->translator->trans('message.unsupportedlanguage', [], 'base'));
                $isOk = false;
            }

            if (true === $isOk) {
                $this->account->create($handle, $password, $locale);
                $this->addFlash('notice', $this->translator->trans('message.accountcreated', [], 'base'));
                return $this->redirectToRoute('account_login');
            }
        }

        return $this->render('base/security/register.html.twig', [
            'form' => $form->createView()
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

        return $this->render('base/security/impersonate.html.twig', ['users' => $users]);
    }

    /**
     * @param array $locales
     * @param array $defaultData
     * @return FormInterface
     */
    private function getAccountForm(array $locales, array $defaultData): FormInterface
    {
        $choices = [];
        foreach ($locales as $item) {
            $choices[$item] = $item;
        }

        return $this->createFormBuilder($defaultData)
            ->add(
                'handle',
                TextType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 32
                    ]
                ]
            )->add(
                'password',
                PasswordType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 32
                    ]
                ]
            )->add(
                'password2',
                PasswordType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 32
                    ]
                ]
            )->add(
                'locale',
                ChoiceType::class,
                [
                    'choices' => $choices
                ]
            )->add('send', SubmitType::class)
            ->getForm();
    }
}
