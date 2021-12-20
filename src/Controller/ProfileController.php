<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/own", name="profile_own")
     */
    public function showOwnAction(): Response
    {
        return $this->render('profile/showown.html.twig', []);
    }
}
