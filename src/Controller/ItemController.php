<?php


namespace App\Controller;


use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Workflow\Exception\ExceptionInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ItemController extends AbstractController
{
    /**
     * @Route("/item/list", name="item_list")
     * @return Response
     */
    public function itemListAction()
    {
        $repository = $this->getDoctrine()->getRepository(Item::class);
        $items = $repository->findAll();
        return $this->render("item/list.html.twig", ['items' => $items]);
    }

    /**
     * @Route("/item/create", name="item_create")
     * @param Request $request
     */
    public function itemCreateAction(Request $request)
    {

    }

    /**
     * @Route("/item/show/{id}", name="item_show")
     * @param int $id
     */
    public function itemShowAction(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Item::class);
        $item = $repository->find($id);
        return $this->render("item/show.html.twig", ['item' => $item]);
    }

    /**
     * @Route("/item/transfer/{id}/{transition}", name="item_transfer")
     * @param WorkflowInterface $fotoPublishingWorkflow
     * @param Request $request
     * @param Item $item
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
}