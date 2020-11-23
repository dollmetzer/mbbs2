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

use App\Entity\Role;
use App\Form\Type\AdminRoleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminRoleController
 * @IsGranted("ROLE_ADMIN")
 * @package App\Controller
 */
class AdminRoleController extends AbstractController
{
    /**
     * @Route("/admin/role/list", name="admin_role_list")
     * @return Response
     */
    public function roleListAction(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $repository = $this->getDoctrine()->getRepository(Role::class);
        $roles = $repository->findAll();
        return $this->render('admin/role/list.html.twig', ['roles' => $roles]);
    }

    /**
     * @Route("/admin/role/show/{id}", name="admin_role_show")
     * @param int $id
     */
    public function roleShowAction(int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $repository = $this->getDoctrine()->getRepository(Role::class);
        $role = $repository->find($id);
        return $this->render('admin/role/show.html.twig', ['role' => $role]);
    }

    /**
     * @Route("/admin/role/edit/{id}", name="admin_role_edit")
     * @param int $id
     */
    public function roleEditAction(int $id)
    {
        die('not yet implemented');
    }

    /**
     * @Route("/admin/role/delete/{id}", name="admin_role_delete")
     * @param int $id
     */
    public function roleDeleteAction(int $id)
    {
        die('not yet implemented');
    }

    /**
     * @Route("admin/role/create", name="admin_role_create")
     */
    public function roleCreateAction()
    {
        $role = new Role();

        $form = $this->createForm(AdminRoleType::class, $role);

        return $this->render('admin/role/new.html.twig', [
            'form' => $form->createView()
        ]);

    }
}