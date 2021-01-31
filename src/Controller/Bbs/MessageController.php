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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController
{
    /**
     * @Route("/message/mail/list", name="message_mail_list")
     */
    public function mailListAction()
    {
        die('mailListAction');
    }

    /**
     * @Route("/message/discussion/list", name="message_discussion_list")
     */
    public function discussionListAction()
    {
        die('discussionListAction');
    }
}