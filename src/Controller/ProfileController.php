<?php
/**
 * M B B S 2   -   B u l l e t i n   B o a r d   S y s t e m
 * ---------------------------------------------------------
 * A small BBS package for mobile use.
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2014-2022, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Controller;

use App\Domain\ProfilePicture;
use App\Entity\Profile;
use App\Entity\User;
use App\Exception\FileUploadException;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @IsGranted("ROLE_USER")
 */
class ProfileController extends AbstractController
{
    private TranslatorInterface $translator;

    private ManagerRegistry $doctrine;

    public function __construct(TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/profile/show", name="profile_own")
     */
    public function showOwnAction(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $repo = $this->doctrine->getRepository(Profile::class);
        $profile = $repo->findOneBy(['owner' => $user->getId()]);
        if (file_exists($this->getPicturePath($profile))) {
            $pictureUrl = $this->getPictureURL($profile);
        } else {
            $pictureUrl = null;
        }

        return $this->render('profile/showown.html.twig', [
            'profile' => $profile,
            'pictureUrl' => $pictureUrl,
        ]);
    }

    /**
     * @Route("/profile/show/{id}", name="profile_show")
     */
    public function showAction($id): Response
    {
        $repo = $this->doctrine->getRepository(Profile::class);
        $profile = $repo->findOneBy($id);

        return $this->render('/profile/show.html.twig',
            ['profile' => $profile]
        );
    }

    /**
     * @Route("/profile/edit", name="profile_edit")
     */
    public function editAction(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $repo = $this->doctrine->getRepository(Profile::class);
        $profile = $repo->findOneBy(['owner' => $user->getId()]);

        $form = $this->getProfileForm($profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirectToRoute('profile_own');
        }

        return $this->render('/profile/edit.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/picture/upload", name="profile_picture_upload", methods={"POST"})
     */
    public function pictureUploadAction(Request $request, ProfilePicture $profilePicture): Response
    {
        if (null !== $request->files->get('profilepicture')) {
            $user = $this->getUser();
            $repo = $this->doctrine->getRepository(Profile::class);
            $profile = $repo->findOneBy(['owner' => $user->getId()]);

            try {
                $profilePicture->processUpload(
                    $request->files->get('profilepicture'),
                    $this->getPicturePath($profile)
                );
            } catch (FileUploadException $e) {
                $this->addFlash('error', $this->translator->trans($e->getMessage()));
            }
        }

        return $this->redirectToRoute('profile_own');
    }

    /**
     * @Route("profile/picture/delete", name="profile_picture_delete")
     */
    public function pictureDeleteAction(): Response
    {
        $user = $this->getUser();
        $repo = $this->doctrine->getRepository(Profile::class);
        $profile = $repo->findOneBy(['owner' => $user->getId()]);
        $picturePath = $this->getPicturePath($profile);
        if (file_exists($picturePath)) {
            unlink($picturePath);
        }

        return $this->redirectToRoute('profile_own');
    }

    private function getProfileForm(Profile $profile): FormInterface
    {
        $zodiacSigns = [];
        foreach ($profile::ENUM_ZODIAC as $sign) {
            $text = $this->translator->trans('text.zodiac_'.$sign, [], 'app');
            $zodiacSigns[$text] = $sign;
        }

        $genders = [];
        foreach ($profile::ENUM_GENDER as $gender) {
            $text = $this->translator->trans('text.gender_'.$gender, [], 'app');
            $genders[$text] = $gender;
        }

        return $this->createFormBuilder($profile, ['translation_domain' => 'app'])
            ->add(
                'displayname',
                TextType::class,
                [
                    'label' => 'form.displayname',
                    'attr' => [
                        'minlength' => 4,
                        'maxlength' => 32,
                    ],
                ]
            )->add(
                'motto',
                TextType::class,
                [
                    'label' => 'form.motto',
                    'required' => false,
                ]
            )->add(
                'gender',
                ChoiceType::class,
                [
                    'label' => 'form.gender',
                    'choices' => $genders,
                ]
            )->add(
                'zodiac',
                ChoiceType::class,
                [
                    'label' => 'form.zodiac-sign',
                    'choices' => $zodiacSigns,
                ]
            )->add('send', SubmitType::class)
            ->getForm();
    }

    protected function getPictureURL(Profile $profile): string
    {
        if (empty($this->getPicturePath($profile))) {
            return '';
        }
        $package = new Package(new EmptyVersionStrategy());

        return $package->getUrl('img/profile/'.$profile->getId().'.jpg');
    }

    protected function getPicturePath(Profile $profile): string
    {
        return realpath(__DIR__.'/../../public/img/profile').'/'.$profile->getId().'.jpg';
    }
}
