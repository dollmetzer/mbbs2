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
use App\Entity\Workflow;
use App\Form\Type\AdminStateType;
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
     * @return Response
     */
    public function stateShowAction(int $id): Response
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
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function stateEditAction(Request $request, int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(State::class);
        $state = $repository->find($id);

        return $this->stateFormProcess($state, $request);
    }

    /**
     * @Route("state/create", name="admin_state_create")
     * @param Request $request
     * @return Response
     */
    public function stateCreateAction(Request $request): Response
    {
        $state = new State();

        return $this->stateFormProcess($state, $request);
    }

    /**
     * @param State $state
     * @param Request $request
     * @return Response
     */
    private function stateFormProcess(State $state, Request $request): Response
    {
        $workflowRepository = $this->getDoctrine()->getRepository(Workflow::class);
        $workflows = $workflowRepository->findAll();

        $form = $this->createForm(AdminStateType::class, $state, ['workflows' => $workflows]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $state = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($state);
            $entityManager->flush();

            return $this->redirectToRoute('admin_state_list');
        }
        return $this->render('admin/state/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("state/delete/{id}", name="admin_state_delete")
     * @param int $id
     */
    public function stateDeleteAction(int $id): void
    {
        die('no delete yet');
    }
}