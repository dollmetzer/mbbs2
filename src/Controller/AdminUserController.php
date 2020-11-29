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

use App\Entity\User;
use App\Form\Type\AdminUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user/list", name="admin_user_list")
     * @return Response
     */
    public function userListAction(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();
        return $this->render('admin/user/list.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/admin/user/show/{id}", name="admin_user_show")
     * @param int $id
     * @return Response
     */
    public function userShowAction(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($id);
        return $this->render('admin/user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/admin/user/edit/{id}", name="admin_user_edit")
     * @param int $id
     * @return Response
     */
    public function userEditAction(int $id): Response
    {
        die('not yet implemented');
    }

    /**
     * @Route("/admin/user/delete/{id}", name="admin_user_delete")
     * @param int $id
     */
    public function userDeleteAction(int $id)
    {
        die('not yet implemented');
    }

    /**
     * @Route("admin/user/create", name="admin_user_create")
     */
    public function userCreateAction()
    {
        $user = new User();
        $form = $this->createForm(AdminUserType::class, $user);

        return $this->render('admin/user/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}