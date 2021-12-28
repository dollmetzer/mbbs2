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
use App\Domain\Invitation as InvitationDomain;
use App\Entity\Invitation as InvitationEntity;
use App\Entity\User;
use App\Exception\InvitationException;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
use Symfony\Contracts\Translation\TranslatorInterface;

class InvitationController extends AbstractController
{
    private ManagerRegistry $doctrine;

    private SessionInterface $session;

    private TranslatorInterface $translator;

    private LoggerInterface $logger;

    private InvitationDomain $invitationDomain;

    private Account $account;

    public function __construct(
        TranslatorInterface $translator,
        ManagerRegistry $doctrine,
        SessionInterface $session,
        LoggerInterface $logger,
        InvitationDomain $invitationDomain,
        Account $account
    ) {
        $this->doctrine = $doctrine;
        $this->session = $session;
        $this->translator = $translator;
        $this->logger = $logger;
        $this->invitationDomain = $invitationDomain;
        $this->account = $account;
    }

    /**
     * @Route("/account/invitation/create", name="account_create_invitation")
     * @IsGranted("ROLE_USER")
     */
    public function createAction(): Response
    {
        if (true !== $this->getParameter('invitation')) {
            return $this->redirectToRoute('index_index');
        }

        $repo = $this->doctrine->getRepository(InvitationEntity::class);
        $invitationCode = $this->session->get('invitationCode');
        $invitation = $repo->findOneBy(['code' => $invitationCode]);

        if (null !== $invitation) {
            // check expired
            $diff = $invitation->getExpirationDateTime()->diff(new DateTimeImmutable());
            $minutes = ($diff->days * 24 * 60) +
                ($diff->h * 60) + $diff->i;
            if ($minutes > 0) {
                $invitation = null;
            }
        }

        if (null === $invitation) {
            while (null === $invitation) {
                $invitationCode = substr(md5(random_bytes(16)), 0, 16);
                echo "$invitationCode\n";
                $invitation = $repo->findBy(['code' => $invitationCode]);
            }
            $invitation = new InvitationEntity();
            $invitation->setCode($invitationCode);
            $invitation->setOriginator($this->getUser());
            $invitation->setExpirationDateTime(new DateTimeImmutable('+15 min'));

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($invitation);
            $entityManager->flush();
            $this->session->set('invitationCode', $invitationCode);
        }

        return $this->render('invitation/create.html.twig', [
            'invitation' => $invitation,
        ]);
    }

    /**
     * @Route("account/invitation/accept/{code}", name="account_accept_invitation")
     */
    public function acceptAction(string $code): Response
    {
        try {
            $invitation = $this->invitationDomain->getInvitation($code);
        } catch (InvitationException $e) {
            $this->addFlash('error', $this->translator->trans($e->getMessage(), [], 'app'));

            return $this->redirectToRoute('index_index');
        }

        $this->session->set('invitedBy', $invitation->getOriginator()->getId());
        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($invitation);
        $entityManager->persist($invitation);
        $entityManager->flush();

        return $this->redirectToRoute('account_invitation_create_account');
    }

    /**
     * @Route("account/invitation/accept", name="account_accept_invitation_form")
     */
    public function acceptFormAction(Request $request)
    {
        $form = $this->getInvitationForm([]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $code = $data['1'].$data['2'].$data['3'].$data['4'];

            try {
                $invitation = $this->invitationDomain->getInvitation($code);
            } catch (InvitationException $e) {
                $this->logger->info($e->getMessage());
                $translatedMessage = $e->getMessage();
                if (InvitationException::ERROR_ILLEGAL_CODE === $translatedMessage) {
                    $translatedMessage = $this->translator->trans('message.invitation.illegal-code', [], 'app');
                } elseif (InvitationException::ERROR_EXPIRED_CODE === $translatedMessage) {
                    $translatedMessage = $this->translator->trans('message.invitation.expired-code', [], 'app');
                }
                $this->addFlash('error', $translatedMessage);

                return $this->redirectToRoute('account_accept_invitation_form');
            }

            $this->session->set('invitedBy', $invitation->getOriginator()->getId());
            $entityManager = $this->doctrine->getManager();
            $entityManager->remove($invitation);
            $entityManager->persist($invitation);
            $entityManager->flush();

            return $this->redirectToRoute('account_invitation_create_account');
        }

        return $this->render('invitation/accept-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("account/invitation/create/account", name="account_invitation_create_account")
     */
    public function createAccount(Request $request): Response
    {
        $invitedBy = $this->session->get('invitedBy');
        if (!$invitedBy) {
            $this->addFlash('error', $this->translator->trans('message.invitation.illegal-code', [], 'app'));

            return $this->redirectToRoute('index_index');
        }

        $locales = $this->getParameter('locales');
        $currentLanguage = $request->getSession()->get('_locale');

        $form = $this->getAccountForm($locales, ['locale' => $currentLanguage]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $isOk = true;

            $password = $data['password'];
            if ($password !== $data['password2']) {
                $this->addFlash('error', $this->translator->trans('message.differentpasswords', [], 'app'));
                $isOk = false;
            }

            $repo = $this->doctrine->getRepository(User::class);
            $username = $data['username'];
            $user = $repo->findOneBy(['username' => $username]);
            if ($user) {
                $this->addFlash('error', $this->translator->trans('message.handleexists', [], 'app'));
                $isOk = false;
            }

            $locale = $data['locale'];
            if (!in_array($locale, $locales)) {
                $this->addFlash('error', $this->translator->trans('message.unsupportedlanguage', [], 'app'));
                $isOk = false;
            }

            if (true === $isOk) {
                $registrar = $repo->find($invitedBy);
                $this->account->create($username, $password, $locale, $registrar);

                $this->addFlash('info', $this->translator->trans('message.accountcreated', [], 'app'));
                return $this->redirectToRoute('account_login');
            }
        }

        return $this->render('invitation/account_application.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getInvitationForm(array $defaultData): FormInterface
    {
        return $this->createFormBuilder($defaultData)
            ->add(
                '1',
                TextType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 4,
                    ],
                ]
            )->add(
                '2',
                TextType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 4,
                    ],
                ]
            )->add(
                '3',
                TextType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 4,
                    ],
                ]
            )->add(
                '4',
                TextType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 4,
                    ],
                ]
            )->add(
                'send',
                SubmitType::class,
            )->getForm();
    }

    private function getAccountForm(array $locales, array $defaultData): FormInterface
    {
        $choices = [];
        foreach ($locales as $item) {
            $choices[$item] = $item;
        }

        return $this->createFormBuilder($defaultData, ['translation_domain' => 'app'])
            ->add(
                'username',
                TextType::class,
                [
                    'label' => 'form.username',
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 32,
                    ],
                ]
            )->add(
                'password',
                PasswordType::class,
                [
                    'label' => 'form.password',
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 32,
                    ],
                ]
            )->add(
                'password2',
                PasswordType::class,
                [
                    'label' => 'form.password2',
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 32,
                    ],
                ]
            )->add(
                'locale',
                ChoiceType::class,
                [
                    'label' => 'form.language',
                    'choices' => $choices,
                ]
            )->add(
                'send',
                SubmitType::class,
                [
                    'label' => 'form.register',
                ]
            )
            ->getForm();
    }
}
