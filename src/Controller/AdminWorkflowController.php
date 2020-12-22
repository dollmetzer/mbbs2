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

use App\Entity\Workflow;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminWorkflowController
 *
 * @package App\Controller
 */
class AdminWorkflowController extends AbstractController
{
    /**
     * @Route("workflow/list", name="admin_workflow_list")
     */
    public function workflowListAction(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Workflow::class);
        $workflows = $repository->findAll();

        return $this->render(
            'admin/workflow/list.html.twig',
            [
                'workflows' => $workflows,
            ]
        );
    }

    /**
     * @Route("workflow/show/{id}", name="admin_workflow_show")
     * @param int $id
     * @return Response
     */
    public function workflowShowAction(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Workflow::class);
        $workflow = $repository->find($id);

        return $this->render(
            'admin/workflow/show.html.twig',
            [
                'workflow' => $workflow
            ]
        );
    }

    /**
     * @Route("workflow/edit/{id}", name="admin_workflow_edit")
     * @param int $id
     */
    public function workflowEditAction(int $id): void
    {
        die('no edit yet');
    }

    /**
     * @Route("workflow/delete/{id}", name="admin_workflow_delete")
     * @param int $id
     */
    public function workflowDeleteAction(int $id): void
    {
        die('no delete yet');
    }

    /**
     * @Route("workflow/create", name="admin_workflow_create")
     */
    public function workflowCreateAction(): void
    {
        die('no create yet');
    }
}