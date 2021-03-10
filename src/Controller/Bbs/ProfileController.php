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

use App\Domain\Bbs\ProfilePicture;
use App\Entity\Bbs\Profile;
use App\Exception\FileUploadException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ProfileController
 *
 * @package App\Controller
 */
class ProfileController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

   /**
     * @Route("/profile", name="profile_own")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function showOwnAction(): Response
    {
        $user = $this->getUser();
        $repo = $this->getDoctrine()->getRepository(Profile::class);
        $profile = $repo->findOneBy(['owner' => $user->getId()]);
        return $this->render("bbs/profile/showown.html.twig", ['profile' => $profile]);
    }

    /**
     * @Route("/profile/show/{uuid}", name="profile_show")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function showAction(string $uuid): Response
    {
        $repo = $this->getDoctrine()->getRepository(Profile::class);
        $profile = $repo->find($uuid);
        return $this->render("bbs/profile/show.html.twig", ['profile' => $profile]);
    }

    /**
     * @Route("/profile/edit", name="profile_edit")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function editAction(Request $request): Response
    {
        $user = $this->getUser();
        $repo = $this->getDoctrine()->getRepository(Profile::class);
        $profile = $repo->findOneBy(['owner' => $user->getId()]);

        $form = $this->getProfileForm($profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirectToRoute('profile_own');
        }

        return $this->render("bbs/profile/edit.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/profile/picture/upload", name="profile_picture_upload")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function pictureUploadAction(Request $request, ProfilePicture $profilePicture): Response
    {
        if (null !== $request->files->get('profilepicture')) {
            $user = $this->getUser();
            $repo = $this->getDoctrine()->getRepository(Profile::class);
            $profile = $repo->findOneBy(['owner' => $user->getId()]);

            try {
                $profilePicture->processUpload(
                    $request->files->get('profilepicture'),
                    '/var/www/mbbs2/public/img/profile/test.jpg'
                );
            } catch(FileUploadException $e) {
                $this->addFlash('error', $this->translator->trans($e->getMessage()));
            }
        }

        return $this->redirectToRoute('profile_own');
    }

    protected function getProfileForm(Profile $profile): FormInterface
    {
        $zodiacSigns = [];
        foreach($profile::ENUM_ZODIAC as $sign) {
            $text = $this->translator->trans('text.zodiac_' . $sign, [], 'bbs');
            $zodiacSigns[$text] = $sign;
        }

        $genders = [];
        foreach($profile::ENUM_GENDER as $gender) {
            $text = $this->translator->trans('text.gender_'.$gender, [], 'bbs');
            $genders[$text] = $gender;
        }

        return $this->createFormBuilder($profile)
            ->add(
                'displayname',
                TextType::class,
                [
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 32
                    ]
                ]
            )->add(
                'realname',
                TextType::class,
                []
            )->add(
                'motto',
                TextType::class,
                []
            )->add(
                'gender',
                ChoiceType::class,
                ['choices' => $genders]
            )->add(
                'zodiac',
                ChoiceType::class,
                ['choices' => $zodiacSigns]
            )->add('send', SubmitType::class)
            ->getForm();

    }
}