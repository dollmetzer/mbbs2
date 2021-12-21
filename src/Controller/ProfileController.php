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

use App\Entity\Profile;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfileController extends AbstractController
{
    private TranslatorInterface $translator;

    private ManagerRegistry $doctrine;

    public function __construct(TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/profile/own", name="profile_own")
     * @IsGranted("ROLE_USER")
     */
    public function showOwnAction(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $repo = $this->doctrine->getRepository(Profile::class);
        $profile = $repo->findOneBy(['owner' => $user->getId()]);

        return $this->render('profile/showown.html.twig', [
            'profile' => $profile,
        ]);
    }

    /**
     * @Route("/profile/show/{id}", name="profile_show")
     */
    public function showAction($id): Response
    {
        $repo = $this->doctrine->getRepository(Profile::class);
        $profile = $repo->findOneBy($id);

        return $this->render('/profile/show.html.twig',
            ['profile' => $profile]
        );
    }
}
