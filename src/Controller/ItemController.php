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

use App\Entity\Item;
use App\Form\Type\ItemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Workflow\Exception\ExceptionInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ItemController
 *
 * @IsGranted("ROLE_CONTENT")
 * @package App\Controller
 */
class ItemController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * AdminWorkflowController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/item/list", name="item_list")
     * @param WorkflowInterface $photoPublishingStateMachine
     * @return Response
     */
    public function itemListAction(WorkflowInterface $photoPublishingStateMachine): Response
    {
        $places = $photoPublishingStateMachine->getDefinition()->getPlaces();
        $repository = $this->getDoctrine()->getRepository(Item::class);
        $items = $repository->findAll();
        return $this->render(
            "item/list.html.twig",
            [
                'items' => $items,
                'places' => $places,
                'place' => ''
            ]
        );
    }

    /**
     * @Route("/item/list/{place}", name="item_filtered_list")
     * @param WorkflowInterface $photoPublishingStateMachine
     * @param string $place
     * @return Response
     */
    public function itemFilteredListAction(WorkflowInterface $photoPublishingStateMachine, string $place): Response
    {
        $places = $photoPublishingStateMachine->getDefinition()->getPlaces();
        if(!in_array($place, $places)) {
            $this->addFlash('error', $this->translator->trans('workflow.message.unknownstate'));
            return $this->redirect($this->generateUrl('item_list'));
        }
        $repository = $this->getDoctrine()->getRepository(Item::class);
        $items = $repository->findBy(['marking' => $place]);
        return $this->render(
            "item/list.html.twig",
            [
                'items' => $items,
                'places' => $places,
                'place' => $place
            ]
        );
    }

    /**
     * @Route("/item/create", name="item_create")
     * @param WorkflowInterface $photoPublishingStateMachine
     * @param Request $request
     * @return Response
     */
    public function itemCreateAction(WorkflowInterface $photoPublishingStateMachine, Request $request): Response
    {
        $item = new Item();

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();

            $initialPlace = $photoPublishingStateMachine->getDefinition()->getInitialPlaces();
            $item->setMarking($initialPlace[0]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('item_list');
        }
        return $this->render('item/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/item/show/{id}", name="item_show")
     * @param int $id
     * @return Response
     */
    public function itemShowAction(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Item::class);
        $item = $repository->find($id);
        return $this->render("item/show.html.twig", ['item' => $item]);
    }

    /**
     * @Route("/item/transfer/{id}/{transition}", name="item_transfer")
     * @param WorkflowInterface $photoPublishingStateMachine
     * @param Request $request
     * @param Item $item
     * @param string $transition
     * @return string
     */
    public function itemTransferAction(WorkflowInterface $photoPublishingStateMachine, Request $request, Item $item, string $transition)
    {
        try {
            $photoPublishingStateMachine->apply($item, $transition);
            $this->getDoctrine()->getManager()->flush();
        } catch (ExceptionInterface $e) {
            //@todo ...
        }
        $target = $this->generateUrl('item_show', ['id' => $item->getId()]);
        return $this->redirect($target);
    }
}