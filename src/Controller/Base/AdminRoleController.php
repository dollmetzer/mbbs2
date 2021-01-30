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

namespace App\Controller\Base;

use App\Entity\Base\Role;
use App\Form\Type\AdminRoleType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AdminRoleController
 *
 * @IsGranted("ROLE_ADMIN")
 * @package App\Controller
 */
class AdminRoleController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * AdminRoleController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    )
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/admin/role/list", name="admin_role_list")
     * @return Response
     */
    public function roleListAction(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Role::class);
        $roles = $repository->findAll();
        return $this->render('admin/role/list.html.twig', ['roles' => $roles]);
    }

    /**
     * @Route("/admin/role/show/{id}", name="admin_role_show")
     * @param int $id
     * @return Response
     */
    public function roleShowAction(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Role::class);
        $role = $repository->find($id);
        return $this->render('admin/role/show.html.twig', ['role' => $role]);
    }

    /**
     * @Route("/admin/role/edit/{id}", name="admin_role_edit")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function roleEditAction(int $id, Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Role::class);
        $role = $repository->find($id);

        if (empty($role)) {
            $this->addFlash('error', $this->translator->trans('admin.message.unknownrole'));
            return $this->redirectToRoute('admin_role_list');
        }

        return $this->roleFormProcess($role, $request);
    }

    /**
     * @Route("/admin/role/delete/{id}", name="admin_role_delete")
     * @param int $id
     * @return Response
     */
    public function roleDeleteAction(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Role::class);
        $role = $repository->find($id);
        if (null !== $role) {
            $this->entityManager->remove($role);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('admin.message.deletedrole'));
        } else {
            $this->addFlash('error', $this->translator->trans('admin.message.unknownrole'));
        }
        return $this->redirectToRoute('admin_role_list');
    }

    /**
     * @Route("admin/role/create", name="admin_role_create")
     * @param Request $request
     * @return Response
     */
    public function roleCreateAction(Request $request): Response
    {
        $role = new Role();
        return $this->roleFormProcess($role, $request);
    }

    private function roleFormProcess(Role $role, Request $request): Response
    {
        $form = $this->createForm(AdminRoleType::class, $role);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $role = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($role);
            $entityManager->flush();

            return $this->redirectToRoute('admin_role_list');
        }

        return $this->render('admin/role/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}