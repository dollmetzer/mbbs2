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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminStateController
 *
 * @IsGranted("ROLE_ADMIN")
 * @package App\Controller
 */
class AdminStateController extends AbstractController
{
    /**
     * @Route("state/list", name="admin_state_list")
     */
    public function stateListAction(): Response
    {
        $repository = $this->getDoctrine()->getRepository(State::class);
        $states = $repository->findAll();

        return $this->render(
            'admin/state/list.html.twig',
            [
                'states' => $states,
            ]
        );
    }

    /**
     * @Route("state/show/{id}", name="admin_state_show")
     * @param int $id
     */
    public function workflowShowAction(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(State::class);
        $state = $repository->find($id);

        return $this->render(
            'admin/state/show.html.twig',
            [
                'state' => $state
            ]
        );
    }

    /**
     * @Route("state/edit/{id}", name="admin_state_edit")
     * @param int $id
     */
    public function workflowEditAction(int $id): void
    {
        die('no edit yet');
    }

    /**
     * @Route("state/delete/{id}", name="admin_state_delete")
     * @param int $id
     */
    public function workflowDeleteAction(int $id): void
    {
        die('no delete yet');
    }

    /**
     * @Route("state/create", name="admin_state_create")
     */
    public function workflowCreateAction(): void
    {
        die('no create yet');
    }
}