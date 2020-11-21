<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleController extends AbstractController
{
    /**
     * @Route("admin/role/list", name="admin_role_list")
     * @return Response
     */
    public function listAction(): Response
    {
        return $this->render('admin/role/list');
    }
}