<?php


namespace App\Controller;

use App\Entity\Role;
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
     * @Route("/admin/role/edit/{id}", name="admin_role_show")
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
        die('not yet implemented');
    }
}