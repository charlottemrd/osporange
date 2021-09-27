<?php

namespace App\Form;

use App\Entity\Modalites;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;



class ModalitesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pourcentage',IntegerType::class,['required'=>true])
            ->add('conditions', ChoiceType::class, ['required' => true,'placeholder'=>'',
                'choices'  => [
                    'changer de phase vers cadrage' =>'changer de phase vers cadrage',
                    'changer de phase vers en etude' =>'changer de phase vers en etude',
                    'changer de phase vers en construction' =>'changer de phase vers en construction',
                    'changer de phase vers en test' =>'changer de phase vers en test',
                    'changer de phase vers en recette' =>'changer de phase vers en recette',
                    'changer de phase vers en production' =>'changer de phase vers en production',
                ]])
            ->add('description',TextareaType::class, array('required' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Modalites::class,
        ]);
    }
}
