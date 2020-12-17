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

namespace App\Controller;

use App\Entity\Item;
use App\Form\Type\ItemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Workflow\Dumper\GraphvizDumper;
use Symfony\Component\Workflow\Exception\ExceptionInterface;
use Symfony\Component\Workflow\WorkflowInterface;

/**
 * Class ItemController
 *
 * @package App\Controller
 */
class ItemController extends AbstractController
{
    /**
     * @Route("/item/list", name="item_list")
     * @return Response
     */
    public function itemListAction(WorkflowInterface $fotoPublishingStateMachine)
    {
        $places = $fotoPublishingStateMachine->getDefinition()->getPlaces();
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
     * @return Response
     */
    public function itemFilteredListAction(WorkflowInterface $fotoPublishingStateMachine, string $place)
    {
        $places = $fotoPublishingStateMachine->getDefinition()->getPlaces();
        if(!in_array($place, $places)) {
            $this->addFlash('error', '');
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
     * @param Request $request
     */
    public function itemCreateAction(WorkflowInterface $fotoPublishingStateMachine, Request $request): Response
    {
        $item = new Item();

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();

            $initialPlace = $fotoPublishingStateMachine->getDefinition()->getInitialPlaces();
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
     */
    public function itemShowAction(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Item::class);
        $item = $repository->find($id);
        return $this->render("item/show.html.twig", ['item' => $item]);
    }

    /**
     * @Route("/item/transfer/{id}/{transition}", name="item_transfer")
     * @param WorkflowInterface $fotoPublishingStateMachine
     * @param Request $request
     * @param Item $item
     * @param string $transition
     * @return string
     */
    public function itemTransferAction(WorkflowInterface $fotoPublishingStateMachine, Request $request, Item $item, string $transition)
    {
        try {
            $fotoPublishingStateMachine->apply($item, $transition);
            $this->getDoctrine()->getManager()->flush();
        } catch (ExceptionInterface $e) {

        }
        $target = $this->generateUrl('item_show', ['id' => $item->getId()]);
        return $this->redirect($target);
    }

    /**
     * @Route("item/dump/{place}")
     */
    public function dumpAction(WorkflowInterface $fotoPublishingStateMachine, string $place)
    {
        $dumper = new GraphvizDumper();
        $dot = $dumper->dump($fotoPublishingStateMachine->getDefinition());

    }
}