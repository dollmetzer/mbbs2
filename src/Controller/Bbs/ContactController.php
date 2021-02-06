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

namespace App\Controller\Bbs;

use App\Entity\Bbs\Circle;
use App\Entity\Bbs\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ContactController
 *
 * @package App\Controller
 */
class ContactController extends AbstractController
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
     * @Route("/contact/list", name="contact_list")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function listAction(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Contact::class);
//        $contacts = $repo->findBy(['owner' => $this->getUser()]);
        $contacts = [
            'A' => [
                [
                    'uuid' => '498372548873525684',
                    'name' => 'Alexandra',
                    'picture' => 'img/profile/Alexandra.jpg',
                    'since' => '02.02.2021',
                ],
            ],
            'D' => [
                [
                    'uuid' => '372548873525684498',
                    'name' => 'Dirk',
                    'picture' => 'img/profile/Dirk.jpg',
                    'since' => '03.02.2021',
                ],
            ],
            'J' => [
                [
                    'uuid' => '725488735256844983',
                    'name' => 'Julia',
                    'picture' => 'img/profile/Julia.jpg',
                    'since' => '31.01.2021',
                ],
            ],
            'S' => [
                [
                    'uuid' => '548873525684498372',
                    'name' => 'Sarah',
                    'picture' => 'img/profile/Sarah.jpg',
                    'since' => '02.02.2021',
                ],
            ]
        ];
        return $this->render("bbs/contact/list.html.twig", ['contacts' => $contacts]);
    }

    /**
     * @Route("/contact/show/{id}", name="contact_show")
     * @IsGranted("ROLE_USER")
     * @param int $id
     * @return Response
     */
    public function showAction(int $id): Response
    {
        $repo = $this->entityManager->getRepository(Contact::class);
        $contact = $repo->find($id);

        if (null === $contact) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcontact'));
            return $this->redirectToRoute('contact_list');
        }
        if ($contact->getOwner()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcontact'));
            return $this->redirectToRoute('contact_list');
        }

        $circleRepo = $this->entityManager->getRepository(Circle::class);
        $circles = $circleRepo->findBy(['owner' => $this->getUser()]);

        return $this->render("bbs/contact/show.html.twig", [
            'contact' => $contact,
            'allCircles' => $circles
        ]);
    }

    /**
     * @Route("contact/edit/{id}", name="contact_edit")
     * @param int $id
     */
    public function editAction(int $id)
    {
        // check, if user is owner

        // ...
        die('edit not yet...');
    }

    /**
     * @Route("/contact/create", name="contact_create")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $contact = new Contact();

        return $this->contactFormProcess($request, $contact);
    }

    /**
     * @param Request $request
     * @param Contact $contact
     * @return Response
     */
    private function contactFormProcess(Request $request, Contact $contact): Response
    {
        $form = $this->getContactForm([]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
print_r($data);
die();
            /*
            $circle = new Circle();
            $circle->setName($data['name']);
            $circle->setOwner($this->getUser());
            $circle->setIsPrimary(false);
            $circle->setTimestamps();

            $this->entityManager->persist($circle);
            $this->entityManager->flush();

            return $this->redirectToRoute('circle_show', ['id' => $circle->getId()]);
            */
        }

        return $this->render('bbs/contact/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param array $defaultData
     * @return FormInterface
     */
    private function getContactForm(array $defaultData): FormInterface
    {
        return $this->createFormBuilder($defaultData)
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
     * @Route("/contact/addcircle/{contactid}/{circleid}", name="contact_addcircle")
     * @param int $contactid
     * @param int $circleid
     * @return Response
     */
    public function addCircleAction(int $contactid, int $circleid): Response
    {
        $contactRepo = $this->entityManager->getRepository(Contact::class);
        $contact = $contactRepo->find($contactid);
        if (null === $contact) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcontact'));
            return $this->redirectToRoute('contact_list');
        }
        if ($contact->getOwner()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcontact'));
            return $this->redirectToRoute('contact_list');
        }

        $circleRepo = $this->entityManager->getRepository(Circle::class);
        $circle = $circleRepo->find($circleid);
        if (null === $circle) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcontact'));
            return $this->redirectToRoute('contact_show', ['id' => $contactid]);
        }
        if ($circle->getOwner()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcontact'));
            return $this->redirectToRoute('contact_show', ['id' => $contactid]);
        }

        $circle->addContact($contact);
        $this->entityManager->persist($circle);
        $this->entityManager->flush();

        return $this->redirectToRoute('contact_show', ['id' => $contactid]);
    }

    /**
     * @Route("/contact/removecircle/{contactid}/{circleid}", name="contact_removecircle")
     * @param int $contactid
     * @param int $circleid
     * @return Response
     */
    public function removeCircleAction(int $contactid, int $circleid): Response
    {
        $contactRepo = $this->entityManager->getRepository(Contact::class);
        $contact = $contactRepo->find($contactid);
        if (null === $contact) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcontact'));
            return $this->redirectToRoute('contact_list');
        }
        if ($contact->getOwner()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcontact'));
            return $this->redirectToRoute('contact_list');
        }

        $circleRepo = $this->entityManager->getRepository(Circle::class);
        $circle = $circleRepo->find($circleid);
        if (null === $circle) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcontact'));
            return $this->redirectToRoute('contact_show', ['id' => $contactid]);
        }
        if ($circle->getOwner()->getId() !== $this->getUser()->getId()) {
            $this->addFlash('error', $this->translator->trans('bbs.error.invalidcontact'));
            return $this->redirectToRoute('contact_show', ['id' => $contactid]);
        }

        $circle->removeContact($contact);
        $this->entityManager->persist($circle);
        $this->entityManager->flush();

        return $this->redirectToRoute('contact_show', ['id' => $contactid]);
    }
}