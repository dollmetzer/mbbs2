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

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AdminUserType
 *
 * @package App\Form\Type
 */
class AdminUserType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'locales' => [],
        ]);
        $resolver->setAllowedTypes('locales', 'array');
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $entity */
        $entity = $options['data'];
        if(!$entity->getId() && !$entity->getPassword()) {
            $passwordRequired = true;
        } else {
            $passwordRequired = false;
        }

        $choices = [];
        foreach($options['locales'] as $item) {
            $choices[$item] = $item;
        }

        $builder->add(
            'handle',
        TextType::class,
            [
                'attr' => [
                    'minlength' => 4,
                    'maxlength' => 32
                ]
            ]
        )->add(
            'password',
            PasswordType::class,
            [
                'required' => $passwordRequired,
                'attr' => [
                    'minlength' => 4,
                    'maxlength' => 32
                ]
            ]
        )->add(
            'locale',
            ChoiceType::class,
            [
                'choices' => $choices
            ]
        )->add(
            'isActive',
            CheckboxType::class,
            [
                'data' => true,
                'required' => false
            ]
        )->add(
            'save',
            SubmitType::class
        );
    }
}