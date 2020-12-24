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

use App\Entity\Transition;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminTransitionController
 *
 * @IsGranted("ROLE_ADMIN")
 * @package App\Controller
 */
class AdminTransitionController extends AbstractController
{
    /**
     * @Route("transition/list", name="admin_transition_list")
     */
    public function stateListAction(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Transition::class);
        $transitions = $repository->findAll();

        return $this->render(
            'admin/transition/list.html.twig',
            [
                'transitions' => $transitions,
            ]
        );
    }

    /**
     * @Route("transition/show/{id}", name="admin_transition_show")
     * @param int $id
     * @return Response
     */
    public function workflowShowAction(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Transition::class);
        $transition = $repository->find($id);

        return $this->render(
            'admin/transition/show.html.twig',
            [
                'transition' => $transition
            ]
        );
    }

    /**
     * @Route("transition/edit/{id}", name="admin_transition_edit")
     * @param int $id
     */
    public function workflowEditAction(int $id): void
    {
        die('no edit yet');
    }

    /**
     * @Route("transition/delete/{id}", name="admin_transition_delete")
     * @param int $id
     */
    public function workflowDeleteAction(int $id): void
    {
        die('no delete yet');
    }

    /**
     * @Route("transition/create", name="admin_transition_create")
     */
    public function workflowCreateAction(): void
    {
        die('no create yet');
    }
}