<?php


namespace App\Controller;


use App\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact/list", name="contact_list")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function listAction(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Contact::class);
        $contacts = $repo->findBy(['owner' => $this->getUser()]);

        return $this->render("bbs/contact/list.html.twig", ['contacts' => $contacts]);
    }

    /**
     * @Route("/contact/show/{id}", name="contact_show")
     * @IsGranted("ROLE_USER")
     * @param int $id
     * @return Response
     */
    public function showAction(int $id): Response
    {
        return $this->render("bbs/contact/show.html.twig");
    }

}