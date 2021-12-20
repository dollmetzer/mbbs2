<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/own", name="profile_own")
     * @IsGranted("ROLE_USER")
     */
    public function showOwnAction(): Response
    {
        return $this->render('profile/showown.html.twig', []);
    }
}
