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
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AdminUserController
 * @IsGranted("ROLE_ADMIN")
 * @package App\Controller
 */
class AdminUserController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/user/list", name="admin_user_list")
     * @return Response
     */
    public function userListAction(): Response
    {
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
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($id);
        return $this->render('admin/user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/admin/user/edit/{id}", name="admin_user_edit")
     * @param int $id
     * @return Response
     */
    public function userEditAction(int $id, Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($id);
        return $this->userFormProcess($user, $request);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="admin_user_delete")
     * @param int $id
     * @return Response
     */
    public function userDeleteAction(int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($id);
        if (null !== $user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Der Nutzer wurde gelöscht.');
        } else {
            $this->addFlash('error', 'Die Nutzer existiert nicht.');
        }
        return $this->redirectToRoute('admin_user_list');
    }

    /**
     * @Route("admin/user/create", name="admin_user_create")
     * @param Request $request
     * @return Response
     */
    public function userCreateAction(Request $request): Response
    {
        $user = new User();
        return $this->userFormProcess($user, $request);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return Response
     */
    private function userFormProcess(User $user, Request $request)
    {
        $form = $this->createForm(AdminUserType::class, $user);
        $oldPassword = $user->getPassword();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if ( empty($user->getPassword()) ) {
                $user->setPassword($oldPassword);
            } else {
                $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}