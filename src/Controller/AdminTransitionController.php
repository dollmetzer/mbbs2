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

use App\Entity\Role;
use App\Entity\State;
use App\Entity\Transition;
use App\Entity\Workflow;
use App\Form\Type\AdminTransitionType;
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
     * @Route("/admin/transition/list", name="admin_transition_list")
     */
    public function transitionListAction(): Response
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
     * @Route("/admin/transition/show/{id}", name="admin_transition_show")
     * @param int $id
     * @return Response
     */
    public function transitionShowAction(int $id): Response
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
     * @Route("/admin/transition/edit/{id}", name="admin_transition_edit")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function transitionEditAction(Request $request, int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Transition::class);
        $transition = $repository->find($id);

        return $this->transitionFormProcess($request, $transition, true);
    }

    /**
     * @Route("/admin/transition/create", name="admin_transition_create")
     * @param Request $request
     * @return Response
     */
    public function transitionCreateAction(Request $request): Response
    {
        $transition = new Transition();

        return $this->transitionFormProcess($request, $transition, false);
    }

    /**
     * @param Request $request
     * @param Transition $transition
     * @param bool $showRoleSelector
     * @return Response
     */
    private function transitionFormProcess(Request $request, Transition $transition, bool $showRoleSelector): Response
    {
        $workflowRepository = $this->getDoctrine()->getRepository(Workflow::class);
        $workflows = $workflowRepository->findAll();

        $stateRepository = $this->getDoctrine()->getRepository(State::class);
        $states = $stateRepository->findBy(['workflow' => $transition->getWorkflow()]);

        $roleRepository = $this->getDoctrine()->getRepository(Role::class);
        $allRoles = $roleRepository->findAll();

        $form = $this->createForm(AdminTransitionType::class, $transition, ['workflows' => $workflows, 'states' => $states]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $transition = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transition);
            $entityManager->flush();

            return $this->redirectToRoute('admin_transition_list');
        }

        return $this->render('admin/transition/form.html.twig', [
            'form' => $form->createView(),
            'allRoles' => $allRoles,
            'item' => $transition,
            'itemId' => $transition->getId(),
            'showRoleSelector' => $showRoleSelector
        ]);
    }

    /**
     * @Route("/admin/transition/delete/{id}", name="admin_transition_delete")
     * @param int $id
     */
    public function transitionDeleteAction(int $id): void
    {
        die('no delete yet');
    }

    /**
     * @Route("/admin/transition/addrole/{id}/{roleId}", name="admin_transition_addrole")
     * @param int $id
     * @param int $roleId
     * @return Response
     */
    public function addRole(int $id, int $roleId): Response
    {
        $transitionRepository = $this->getDoctrine()->getRepository(Transition::class);
        $transition = $transitionRepository->find($id);

        $roleRepo = $this->getDoctrine()->getRepository(Role::class);
        $role = $roleRepo->find($roleId);

        if ((null !== $transition) && (null !== $role)) {
            $transition->addRole($role);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transition);
            $entityManager->flush();
        } else {
            $this->addFlash('error', 'Die Rolle konnte dem Nutzer nicht zugeordnet werden.');
        }

        return $this->redirectToRoute('admin_transition_edit', ['id' =>$id]);
    }

    /**
     * @Route("/admin/transition/deleterole/{id}/{roleId}", name="admin_transition_deleterole")
     * @param int $id
     * @param int $roleId
     * @return Response
     */
    public function deleteRole(int $id, int $roleId): Response
    {
        $transitionRepository = $this->getDoctrine()->getRepository(Transition::class);
        $transition = $transitionRepository->find($id);

        $roleRepo = $this->getDoctrine()->getRepository(Role::class);
        $role = $roleRepo->find($roleId);

        if ((null !== $transition) && (null !== $role)) {
            $transition->removeRole($role);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transition);
            $entityManager->flush();
        } else {
            $this->addFlash('error', 'Die Rolle konnte dem Nutzer nicht zugeordnet werden.');
        }

        return $this->redirectToRoute('admin_transition_edit', ['id' =>$id]);
    }
}