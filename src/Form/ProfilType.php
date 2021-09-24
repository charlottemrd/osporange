<?php

namespace App\Form;
use App\Entity\Profil;
use App\Entity\Fournisseur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EntityType;



class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('tarif')
         ;
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //  'data_class' => Fournisseur::class,
            'data_class' => 'App\Entity\Profil',


        ]);
    }
}
