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

use App\Entity\Circle;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CircleController
 *
 * @IsGranted("ROLE_USER")
 * @package App\Controller
 */
class CircleController extends AbstractController
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
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        LoggerInterface $logger
    )
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->logger = $logger;
    }

    /**
     * @Route("/circle/list", name="circle_list")
     * @return Response
     */
    public function listAction(): Response
    {
        $repo = $this->entityManager->getRepository(Circle::class);
        $circles = $repo->findBy(['owner' => $this->getUser()]);

        return $this->render("bbs/circle/list.html.twig", ['circles' => $circles]);
    }

    /**
     * @Route("circle/show/{id}", name="circle_show")
     */
    public function showAction(int $id)
    {
        $repo = $this->entityManager->getRepository(Circle::class);
        $circle = $repo->find($id);

        if (null === $circle) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcircle'));
            return $this->redirectToRoute('circle_list');
        }
        if ($circle->getOwner()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcircle'));
            return $this->redirectToRoute('circle_list');
        }

        return $this->render('bbs/circle/show.html.twig', ['circle' => $circle]);
    }

    /**
     * @Route("circle/edit/{id}", name="circle_edit")
     * @param int $id
     */
    public function editAction(Request $request, int $id)
    {
        $repo = $this->entityManager->getRepository(Circle::class);
        $circle = $repo->find($id);

        if (null === $circle) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcircle'));
            return $this->redirectToRoute('circle_list');
        }
        if ($circle->getOwner()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcircle'));
            return $this->redirectToRoute('circle_list');
        }

        return $this->circleFormProcess($request, $circle);
    }

    /**
     * @Route("/circle/create", name="circle_create")
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $circle = new Circle();

        return $this->circleFormProcess($request, $circle);
    }

    private function circleFormProcess(Request $request, Circle $circle)
    {
        $form = $this->getCircleForm($circle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $circle = $form->getData();

            if (null === $circle->getId()) {
                $circle->setOwner($this->getUser());
                $circle->setIsPrimary(false);
            }
            $circle->setTimestamps();

            /**
            $circle = new Circle();
            $circle->setName($data->getName());
            $circle->setOwner($this->getUser());
            $circle->setIsPrimary(false);
            $circle->setTimestamps();
*/
            $this->entityManager->persist($circle);
            $this->entityManager->flush();

            return $this->redirectToRoute('circle_show', ['id' => $circle->getId()]);
        }

        return $this->render('bbs/circle/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param array $defaultData
     * @return FormInterface
     */
    private function getCircleForm(Circle $circle): FormInterface
    {
        return $this->createFormBuilder($circle)
            ->add(
                'name',
                TextType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 32
                    ]
                ]
            )->add('send', SubmitType::class)
            ->getForm();;
    }

    /**
     * @Route("circle/delete/{id}", name="circle_delete")
     * @param int $id
     */
    public function deleteAction(int $id)
    {
        $repo = $this->entityManager->getRepository(Circle::class);
        $circle = $repo->find($id);

        if (null === $circle) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcircle'));
            return $this->redirectToRoute('circle_list');
        }
        if ($circle->getOwner()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcircle'));
            return $this->redirectToRoute('circle_list');
        }
        if (true === $circle->isPrimary()) {
            $this->addFlash('error', $this->translator->trans('bbs.error.protectedcircle'));
            return $this->redirectToRoute('circle_list');
        }

        $this->entityManager->remove($circle);
        $this->entityManager->flush();

        return $this->redirectToRoute('circle_list');
    }

}