<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact/list", name="contact_list")
     * @IsGranted("ROLE_USER")
     */
    public function list(): Response
    {
        return $this->render('contact/list.html.twig', []);
    }
}
