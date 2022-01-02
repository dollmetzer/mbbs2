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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class MailController extends AbstractController
{
    /**
     * @Route("/mail/create/{to}", name="mail_create")
     */
    public function createAction(string $to): Response
    {
        return $this->render('mail/create.html.twig', []);
    }

    /**
     * @Route("/mail/read/{id}", name="mail_read")
     */
    public function readAction(int $id): Response
    {
        return $this->render('mail/read.html.twig', []);
    }

    /**
     * @Route("/mail/delete/{id}", name="mail_read")
     */
    public function deleteAction(): Response
    {
        return $this->redirectToRoute('chat_thread_list');
    }
}
