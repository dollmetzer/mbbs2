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
 * Class AdminTransitionType
 *
 * @package App\Form\Type
 */
class AdminTransitionType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'workflows' => [],
            'states' => []
        ]);
        $resolver->setAllowedTypes('workflows', 'array');
        $resolver->setAllowedTypes('states', 'array');
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $workflows = $options['workflows'];
        $states = $options['states'];
        $builder->add(
            'name',
            TextType::class,
            [
                'attr' => [
                    'minlength' => 4,
                    'maxlength' => 32
                ]
            ]
        )->add(
            'workflow',
            ChoiceType::class,
            [
                'choices' => $workflows,
                'choice_value' => 'id',
                'choice_label' => 'name',
            ]
        )->add(
            'fromState',
            ChoiceType::class,
            [
                'choices' => $states,
                'choice_value' => 'id',
                'choice_label' => 'name',
            ]
        )->add(
            'toState',
            ChoiceType::class,
            [
                'choices' => $states,
                'choice_value' => 'id',
                'choice_label' => 'name',
            ]
        )->add(
            'save',
            SubmitType::class
        );
    }
}