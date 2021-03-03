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

namespace App\Controller\Bbs;

use App\Entity\Bbs\Profile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 *
 * @package App\Controller
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile_own")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function listAction(): Response
    {
        $user = $this->getUser();
        $repo = $this->getDoctrine()->getRepository(Profile::class);
        $profile = $repo->findOneBy(['owner' => $user->getId()]);
        return $this->render("bbs/profile/show.html.twig", ['user' => $user, 'profile' => $profile]);
    }

}