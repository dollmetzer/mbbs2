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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ThreadController
 *
 * @package App\Controller\Bbs
 */
class ThreadController extends AbstractController
{
    /**
     * @Route("/thread/list", name="bbs_thread_list")
     * @IsGranted("ROLE_USER")
     */
    public function listAction(): Response
    {
        $threads = [
            [
                'uuid' => '01928783987786',
                'type' => 'group',
                'name' => 'The Good Old Boys',
                'picture' => 'img/group/good_old_boys.jpg',
                'opened' => false,
                'updated' => '2021-02-06 15:18:00',
                'teaser' => 'Zwei von drei User sin in d...',
            ],
            [
                'uuid' => '10928783a9aa78',
                'type' => 'person',
                'name' => 'Sarah',
                'picture' => 'img/profile/Sarah.jpg',
                'opened' => true,
                'updated' => '2021-02-06 12:01:00',
                'teaser' => 'Cool, wir freuen uns!',
            ],
            [
                'uuid' => '92fe8783aa7801',
                'type' => 'person',
                'name' => 'Dirk',
                'picture' => 'img/profile/Dirk.jpg',
                'opened' => true,
                'updated' => '2021-02-05 22:28:00',
                'teaser' => 'Neue 16K Demo von den ThompsonTwins ist am...',
            ],
            [
                'uuid' => 'e8783aa780192f',
                'type' => 'person',
                'name' => 'Alexandra',
                'picture' => 'img/profile/Alexandra.jpg',
                'opened' => false,
                'updated' => '2021-02-05 20:53:00',
                'teaser' => 'Neue Konzerttermine für das 2. Halbjahr. Hoffe, d...',
            ],
            [
                'uuid' => '83aa780192fe87',
                'type' => 'person',
                'name' => 'Julia',
                'picture' => 'img/profile/Julia.jpg',
                'opened' => false,
                'updated' => '2021-02-05 19:46:00',
                'teaser' => 'Irgendwie geht mir das alles auf die Nerven!',
            ],
        ];

        return $this->render('bbs/thread/list.html.twig', ['threads' => $threads]);
    }
}