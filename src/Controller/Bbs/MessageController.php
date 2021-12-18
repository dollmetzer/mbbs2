<?php
/**
 * M B B S 2   -   B u l l e t i n   B o a r d   S y s t e m
 * ---------------------------------------------------------
 * A small BBS package for mobile use.
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2014-2020, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Controller\Bbs;

use Symfony\Component\Routing\Annotation\Route;

class MessageController
{
    /**
     * @Route("/message/new", name="message_create_thread")
     */
    public function createThreadAction(): void
    {
        exit('message_create_thread');
    }

    /**
     * @Route("/message/new/{thread_id}", name="message_create_message")
     */
    public function createMessageAction(string $thread_id): void
    {
        exit("message_create_thread $thread_id");
    }
}
