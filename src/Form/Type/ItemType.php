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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ItemType
 *
 * @package App\Form\Type
 */
class ItemType extends AbstractType
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
                'attr' => [
                    'label' => 'item.form.name',
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