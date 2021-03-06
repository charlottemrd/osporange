<?php

namespace App\Form;

use App\Entity\Idmonthbm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class IdmonthbmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bilanmensuels', CollectionType::class, array(
                'entry_type'   => BilanmensuelType::class,
                'allow_add'    => false,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype'=> true,
                "row_attr" => [
                    "class" => "d-none"
                ],
            ))
           ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Idmonthbm::class,
        ]);
    }
}
