<?php


namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminWorkflowType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $workflow = $options['data'];
        $states = $workflow->getStates();

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
            'type',
            ChoiceType::class,
            [
                'choices' => [
                    'state_machine' => 'state_machine',
                    'workflow' => 'workflow'
                ]
            ]
        );

        if (0 != count($states)) {
            $builder->add(
                'initialState',
                ChoiceType::class,
                [
                    'choices' => $states,
                    'choice_value' => 'name',
                    'choice_label' => 'name',
                ]
            );
        }

        $builder->add(
            'audit_trail',
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