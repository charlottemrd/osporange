<?php

namespace App\Form;
use App\Entity\Profil;
use App\Entity\Fournisseur;
use App\Form\ProfilType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\CallbackValidator;



class FournisseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('adress')
            ->add('mail')
            ->add('phone')
            ->add('profils', CollectionType::class, array(
                'entry_type'   => ProfilType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype'=> true,
                "row_attr" => [
                    "class" => "d-none"
                ],
            ));
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          //  'data_class' => Fournisseur::class,
            'data_class' => 'App\Entity\Fournisseur',


        ]);
    }
}
