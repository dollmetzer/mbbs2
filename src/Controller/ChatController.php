<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    /**
     * @Route("/chat/thread/list", name="chat_thread_list")
     * @IsGranted("ROLE_USER")
     */
    public function threadList(): Response
    {
        return $this->render('chat/threadlist.html.twig', []);
    }
}
