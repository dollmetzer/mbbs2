<?php
/**
 * C O M P A R E   2   W O R K F L O W S
 * -------------------------------------
 * A small comparison of two workflow implementations
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2020, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AdminStateType
 *
 * @package App\Form\Type
 */
class AdminStateType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'workflows' => []
        ]);
        $resolver->setAllowedTypes('workflows', 'array');
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $workflows = $options['workflows'];
        $builder->add(
            'name',
            TextType::class,
            [
                'attr' => [
                    'minlength' => 4,
                    'maxlength' => 32
                ]
            ]
        );

        if (0 != count($workflows)) {
            $builder->add(
                'workflow',
                ChoiceType::class,
                [
                    'choices' => $workflows,
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                ]
            );
        }

        $builder->add(
            'onEnter',
            TextType::class,
            [
                'required' => false,
                'attr' => [

                    'maxlength' => 128
                ]
            ]
        )->add(
            'onLeave',
            TextType::class,
            [
                'required' => false,
                'attr' => [
                    'maxlength' => 128
                ]
            ]
        )->add(
            'save',
            SubmitType::class
        );
    }
}