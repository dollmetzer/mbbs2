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
