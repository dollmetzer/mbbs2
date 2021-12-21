<?php
/**
 * M B B S 2   -   B u l l e t i n   B o a r d   S y s t e m
 * ---------------------------------------------------------
 * A small BBS package for mobile use.
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2014-2022, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Controller;

use App\Domain\Account;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccountController extends AbstractController
{
    private Account $account;

    private TranslatorInterface $translator;

    private EntityManagerInterface $entityManager;

    public function __construct(
        Account $account,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager
    ) {
        $this->account = $account;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/account/login", name="account_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/account/logout", name="account_logout", methods={"GET"})
     */
    public function logout(): void
    {
        // controller can be blank: it will never be called!
    }

    /**
     * @Route("/account/register", name="account_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): response
    {
        if (true === $this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('index_index');
        }

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
            $username = $data['username'];
            $user = $repo->findOneBy(['username' => $username]);
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
                $this->account->create($username, $password, $locale);
                $this->addFlash('notice', $this->translator->trans('message.accountcreated', [], 'base'));

                return $this->redirectToRoute('account_login');
            }
        }

        return $this->render('account/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    private function getAccountForm(array $locales, array $defaultData): FormInterface
    {
        $choices = [];
        foreach ($locales as $item) {
            $choices[$item] = $item;
        }

        return $this->createFormBuilder($defaultData)
            ->add(
                'username',
                TextType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 32,
                    ],
                ]
            )->add(
                'password',
                PasswordType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 32,
                    ],
                ]
            )->add(
                'password2',
                PasswordType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 32,
                    ],
                ]
            )->add(
                'locale',
                ChoiceType::class,
                [
                    'choices' => $choices,
                ]
            )->add('send', SubmitType::class)
            ->getForm();
    }
}
