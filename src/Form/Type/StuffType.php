<?php


namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class StuffType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'item.form.name',
                'attr' => [
                    'minlength' => 4,
                    'maxlength' => 32
                ]
            ]
        )->add(
            'identifier',
            TextType::class,
            [
                'label' => 'item.form.identifier',
                'attr' => [
                    'minlength' => 4,
                    'maxlength' => 32
                ]
            ]
        )->add(
            'isActive',
            CheckboxType::class,
            [
                'label' => 'item.form.active',
                'data' => true,
                'required' => false
            ]
        )->add(
            'save',
            SubmitType::class,
            [
                'label' => 'base.form.save',
            ]
        );
    }
}