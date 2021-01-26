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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class IndexController
 *
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index_index")
     */
    public function indexAction(): Response
    {
        return $this->render("index/index.html.twig");
    }

    /**
     * @Route("/terms", name="index_terms")
     */
    public function termsAction(): Response
    {
        return $this->render('index/terms.html.twig', []);
    }

    /**
     * @Route("/privacy", name="index_privacy")
     */
    public function privacyAction(): Response
    {
        return $this->render('index/privacy.html.twig', []);
    }

    /**
     * @Route("/imprint", name="index_imprint")
     */
    public function imprintAction(): Response
    {
        return $this->render('index/imprint.html.twig', []);
    }

    /**
     * @Route("/lang/{lang}", name="index_set_language")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param string $lang
     * @return Response
     */
    public function setlangAction(Request $request, TranslatorInterface $translator, string $lang): Response
    {
        $allowedLanguages = ['en','de'];
        $lang = substr(strtolower($lang), 0, 2);
        if (in_array($lang, $allowedLanguages)) {
            $request->getSession()->set('_locale', $lang);
        } else {
            $this->addFlash('error', $translator->trans('base.message.unsupportedlanguage'));
        }

        return $this->redirectToRoute('index_index');
    }
}