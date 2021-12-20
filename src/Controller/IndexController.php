<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index_index")
     */
    public function indexAction(): Response
    {
        return $this->render('index/index.html.twig', []);
    }

    /**
     * @Route("/page/terms", name="index_terms")
     */
    public function termsAction(): Response
    {
        return $this->render('index/terms.html.twig', []);
    }

    /**
     * @Route("/page/privacy", name="index_privacy")
     */
    public function privacyAction(): Response
    {
        return $this->render('index/privacy.html.twig', []);
    }

    /**
     * @Route("/page/imprint", name="index_imprint")
     */
    public function imprintAction(): Response
    {
        return $this->render('index/imprint.html.twig', []);
    }
}
