<?php
/**
 * C O M P A R E   2   W O R K F L O W S
 * -------------------------------------
 * A small comparison of two workflow implementations
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2020, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Controller;

use App\Entity\State;
use App\Entity\Stuff;
use App\Entity\Transition;
use App\Entity\Workflow;
use App\Form\Type\StuffType;
use App\Workflow\Transfer;
use App\Workflow\TransferException;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class StuffController
 *
 * @IsGranted("ROLE_CONTENT")
 * @package App\Controller
 */
class StuffController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Transfer
     */
    private $transfer;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * StuffController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param Transfer $transfer
     * @param TranslatorInterface $translator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Transfer $transfer,
        TranslatorInterface $translator
    )
    {
        $this->entityManager = $entityManager;
        $this->transfer = $transfer;
        $this->translator = $translator;
    }

    /**
     * @Route("stuff/list", name="stuff_list")
     * @return Response
     */
    public function stuffListAction(): Response
    {
        $workflow = $this->getWorkflow();

        $stuffRepository = $this->getDoctrine()->getRepository(Stuff::class);
        $stuff = $stuffRepository->findAll();

        return $this->render(
            'stuff/list.html.twig',
            [
                'stuff' => $stuff,
                'workflow' => $workflow,
                'place' => ''
            ]
        );
    }

    /**
     * @Route("stuff/list/{place}", name="stuff_filtered_list")
     * @param string $place
     * @return Response
     */
    public function stuffFilteredListAction(string $place): Response
    {
        $workflow = $this->getWorkflow();

        $stateRepository = $this->getDoctrine()->getRepository(State::class);
        $state = $stateRepository->findOneBy(['name' => $place]);
        if (!$state) {
            $this->addFlash('error', $this->translator->trans('workflow.message.unknownstate'));
            return $this->redirectToRoute('stuff_list');
        }

        $stuffRepository = $this->getDoctrine()->getRepository(Stuff::class);
        $stuff = $stuffRepository->findBy(['state' => $state]);

        return $this->render(
            'stuff/list.html.twig',
            [
                'stuff' => $stuff,
                'workflow' => $workflow,
                'place' => $state->getName()
            ]
        );
    }

    /**
     * @Route("stuff/create", name="stuff_create")
     * @param Request $request
     * @return Response
     */
    public function stuffCreateAction(Request $request): Response
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
     * @return Response
     */
    public function stuffShowAction(int $id): Response
    {
        $stuffRepository = $this->getDoctrine()->getRepository(Stuff::class);
        $stuff = $stuffRepository->find($id);

        $transitionsRepository = $this->getDoctrine()->getRepository(Transition::class);
        $transitions = $transitionsRepository->findAll();

        return $this->render(
            'stuff/show.html.twig',
            [
                'stuff' => $stuff,
                'transitions' => $transitions
            ]
        );
    }

    /**
     * @Route("/stuff/transfer/{id}/{transitionid}", name="stuff_transfer")
     * @param int $id
     * @param int $transitionid
     * @return Response
     */
    public function stuffTransferAction(int $id, int $transitionid): Response
    {
        $stuffRepository = $this->getDoctrine()->getRepository(Stuff::class);
        $stuff = $stuffRepository->find($id);
        if (empty($stuff)) {
            $this->addFlash('error', $this->translator->trans('workflow.message.unknownstuff'));
            return $this->redirect($this->generateUrl('stuff_list'));
        }

        try {
            $this->transfer->execute($stuff, $transitionid);
        } catch(TransferException $e) {
            $this->addFlash('error', $this->translator->trans($e->getMessage()));
        }
        return $this->redirect($this->generateUrl('stuff_show', ['id' => $stuff->getId()]));
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