<?php


namespace App\Controller;

use App\Entity\Stuff;
use App\Entity\Workflow;
use App\Form\Type\StuffType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StuffController extends AbstractController
{
    /**
     * @Route("stuff/list", name="stuff_list")
     * @return Response
     */
    public function stuffListAction()
    {
        $stuffRepository = $this->getDoctrine()->getRepository(Stuff::class);
        $stuff = $stuffRepository->findAll();

        $workflow = $this->getWorkflow();

        return $this->render(
            'stuff/list.html.twig',
            [
                'stuff' => $stuff,
                'workflow' => $workflow
            ]
        );
    }

    /**
     * @Route("stuff/create", name="stuff_create")
     */
    public function stuffCreateAction(Request $request)
    {
        $stuff = new Stuff();

        $form = $this->createForm(StuffType::class, $stuff);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Stuff $stuff */
            $stuff = $form->getData();

            $workflow = $this->getWorkflow();

            $stuff->setWorkflow($workflow);
            $initialState = $workflow->getInitialState();
            $stuff->setState($initialState);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stuff);
            $entityManager->flush();

            return $this->redirectToRoute('stuff_list');
        }
        return $this->render('stuff/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("stuff/show/{id}", name="stuff_show")
     * @param int $id
     */
    public function stuffShowAction(int $id)
    {
        die("not implemented yet");
    }

    /**
     * @Route("/stuff/transfer/{id}/{transition}", name="stuff_transfer")
     * @param int $id
     * @param int $transition
     */
    public function stuffTransferAction(int $id, int $transition)
    {
        die("not implemented yet");
    }

    /**
     * @return Workflow
     */
    private function getWorkflow(): Workflow
    {
        $workflowRepository = $this->getDoctrine()->getRepository(Workflow::class);
        return $workflowRepository->findOneBy(['name' => 'Foto Publishing']);
    }
}