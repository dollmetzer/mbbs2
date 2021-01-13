<?php


namespace App\Controller;


use App\Entity\Circle;
use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class CircleController extends AbstractController
{
    /**
     * @Route("/circle/list", name="circle_list")
     * @return Response
     */
    public function listAction(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Circle::class);
        $circles = $repo->findBy(['owner' => $this->getUser()]);

        return $this->render("bbs/circle/list.html.twig", ['circles' => $circles]);
    }

}