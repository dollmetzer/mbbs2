<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminUserController extends AbstractController
{
    /**
     * @Route("admin/user/list", name="admin_user_list")
     * @return Response
     */
    public function listAction(): Response
    {
        return $this->render('admin/user/list');
    }
}