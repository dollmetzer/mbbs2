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

namespace App\Controller\Base;

use App\Domain\Base\Account;
use App\Entity\Base\Invitation;
use App\Entity\Base\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class InvitationController.
 */
class InvitationController extends AbstractController
{
    private SessionInterface $session;

    private LoggerInterface $logger;

    private TranslatorInterface $translator;

    private EntityManagerInterface$entityManager;

    private Account $account;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        SessionInterface $session,
        Account $account,
        UserPasswordHasherInterface $passwordHasher,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->session = $session;
        $this->logger = $logger;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->account = $account;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @Route("/account/invite", name="account_invite")
     * @IsGranted("ROLE_USER")
     */
    public function inviteAction(): Response
    {
        if (true !== $this->getParameter('invitation')) {
            return $this->redirectToRoute('index_index');
        }

        return $this->render('base/invitation/invite.html.twig', [
            'invitation' => null,
        ]);
    }

    /**
     * @Route("/account/create/invitation", name="account_create_invitation")
     * @IsGranted("ROLE_USER")
     *
     * @throws Exception
     */
    public function createInvitationAction(): Response
    {
        if (true !== $this->getParameter('invitation')) {
            return $this->redirectToRoute('index_index');
        }

        $repo = $this->entityManager->getRepository(Invitation::class);
        $invitationCode = $this->session->get('invitationCode');
        $invitation = $repo->findOneBy(['code' => $invitationCode]);

        if (null !== $invitation) {
            // check expired
            $diff = $invitation->getExpiration()->diff(new DateTimeImmutable());
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
            $invitation = new Invitation();
            $invitation->setCode($invitationCode);
            $invitation->setOriginator($this->getUser());
            $invitation->setExpiration(new DateTimeImmutable('+15 min'));

            $this->entityManager->persist($invitation);
            $this->entityManager->flush();
            $this->session->set('invitationCode', $invitationCode);
        }

        return $this->render('base/invitation/invite.html.twig', [
            'invitation' => $invitation,
        ]);
    }

    /**
     * @Route("account/invitation", name="account_invitation")
     */
    public function invitationFormAction(Request $request): Response
    {
        $form = $this->getInvitationForm([]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $code = $data['1'].$data['2'].$data['3'].$data['4'];

            try {
                $invitation = $this->getInvitation($code);
            } catch (Exception $e) {
                $this->logger->info($e->getMessage());
                $this->addFlash('error', $this->translator->trans($e->getMessage(), [], 'base'));

                return $this->redirectToRoute('account_invitation');
            }

            $this->session->set('invitedBy', $invitation->getOriginator()->getId());
            $this->entityManager->remove($invitation);
            $this->entityManager->persist($invitation);
            $this->entityManager->flush();

            return $this->redirectToRoute('account_invitation_create_account');
        }

        return $this->render('base/invitation/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("account/accept/invitation/{code}", name="account_accept_invitation")
     */
    public function acceptInvitationAction(string $code): Response
    {
        try {
            $invitation = $this->getInvitation($code);
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
            $this->addFlash('error', $this->translator->trans($e->getMessage(), [], 'base'));

            return $this->redirectToRoute('account_invitation');
        }

        $this->session->set('invitedBy', $invitation->getOriginator()->getId());
        $this->entityManager->remove($invitation);
        $this->entityManager->persist($invitation);
        $this->entityManager->flush();

        return $this->redirectToRoute('account_invitation_create_account');
    }

    /**
     * @Route("account/invitation/create/account", name="account_invitation_create_account")
     */
    public function createAccount(Request $request): Response
    {
        $repo = $this->entityManager->getRepository(User::class);
        $invitedBy = $this->session->get('invitedBy');

        if (!$invitedBy) {
            $this->addFlash('error', $this->translator->trans('error.invitation.invalid', [], 'base'));

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
                $registrar = $repo->find($invitedBy);
                $user = $this->account->create($handle, $password, $locale, $registrar);

                $this->addFlash('notice', $this->translator->trans('message.accountcreated', [], 'base'));

                return $this->redirectToRoute('account_login');
            }
        }

        return $this->render('base/invitation/account_application.html.twig', [
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
            )->add('send', SubmitType::class)
            ->getForm();
    }

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

    /**
     * @return Invitation|object
     *
     * @throws Exception
     */
    private function getInvitation(string $code)
    {
        $repo = $this->entityManager->getRepository(Invitation::class);
        $invitation = $repo->findOneBy(['code' => $code]);

        if (!$invitation) {
            throw new Exception('error.invitation.invalid');
        }

        $now = new DateTimeImmutable('now');
        if ($now > $invitation->getExpiration()) {
            throw new Exception('error.invitation.invalid');
        }

        return $invitation;
    }
}
