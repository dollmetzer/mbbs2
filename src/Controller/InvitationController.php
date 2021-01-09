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

use App\Entity\Invitation;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class InvitationController
 *
 * @package App\Controller
 */
class InvitationController extends AbstractController
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        SessionInterface $session,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    )
    {
        $this->session = $session;
        $this->logger = $logger;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/account/invite", name="account_invite")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function inviteAction(): Response
    {
        if (true !== $this->getParameter('invitation')) {
            return $this->redirectToRoute('index_index');
        }

        return $this->render('invitation/invite.html.twig', [
            'invitation' => null
        ]);
    }

    /**
     * @Route("/account/create/invitation", name="account_create_invitation")
     * @IsGranted("ROLE_USER")
     * @return Response
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
                $invitationCode = substr(md5(random_bytes(16)),0,16);
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

        return $this->render('invitation/invite.html.twig', [
            'invitation' => $invitation
        ]);
    }

    /**
     * @Route("account/invitation", name="account_invitation")
     * @return Response
     */
    public function invitationformAction(Request $request): Response
    {
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add(
                '1',
                TextType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 4
                    ]
                ]
            )->add(
                '2',
                TextType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 4
                    ]
                ]
            )->add(
                '3',
                TextType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 4
                    ]
                ]
            )->add(
                '4',
                TextType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 4
                    ]
                ]
            )->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $code = $data['1'].$data['2'].$data['3'].$data['4'];

            try {
                $invitation = $this->getInvitation($code);
            } catch(Exception $e) {
                $this->logger->info($e->getMessage());
                $this->addFlash('error', $this->translator->trans($e->getMessage()));
                return $this->redirectToRoute('account_invitation');
            }

            $this->session->set('invitedBy', $invitation->getOriginator()->getId());
            $this->entityManager->remove($invitation);
            $this->entityManager->persist();
            $this->entityManager->flush();
            return $this->redirectToRoute('account_invitation_create_account');
        }
        return $this->render('invitation/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("account/accept/invitation/{code}", name="account_accept_invitation")
     * @return Response
     */
    public function acceptInvitationAction(string $code): Response
    {
        try {
            $invitation = $this->getInvitation($code);
        } catch(Exception $e) {
            $this->logger->info($e->getMessage());
            $this->addFlash('error', $this->translator->trans($e->getMessage()));
            return $this->redirectToRoute('account_invitation');
        }

        $this->session->set('invitedBy', $invitation->getOriginator()->getId());
        $this->entityManager->remove($invitation);
        $this->entityManager->persist();
        $this->entityManager->flush();

        return $this->redirectToRoute('account_invitation_create_account');
    }

    /**
     * @Route("account/invitation/create/account", name="account_invitation_create_account")
     * @return Response
     */
    public function createAccount(): Response
    {
        die('Create Account');
        // todo: first check Session invitedBy
    }


    /**
     * @param string $code
     * @return Invitation|object
     * @throws Exception
     */
    private function getInvitation(string $code) {
        $repo = $this->entityManager->getRepository(Invitation::class);
        $invitation = $repo->findOneBy(['code' => $code]);

        if(!$invitation) {
            throw new Exception('base.error.invitation.invalid');
        }

        $now = new DateTimeImmutable('now');
        if($now > $invitation->getExpiration()) {
            throw new Exception('base.error.invitation.invalid');
        }
        return $invitation;
    }
}