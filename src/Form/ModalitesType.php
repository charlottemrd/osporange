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
            ->add('pourcentage',IntegerType::class,['required'=>true], ['attr' => [
                'class' => 'id_nombreprofil']])
            ->add('conditions', ChoiceType::class, ['required' => true,'placeholder'=>'',
                'choices'  => [
                    'date T1 atteinte' =>'date T1 atteinte',
                    'date T2 atteinte' =>'date T2 atteinte',
                    'date T3 atteinte' =>'date T3 atteinte',
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
