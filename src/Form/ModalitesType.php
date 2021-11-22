<?php

namespace App\Form;

use App\Entity\Modalites;
use Doctrine\DBAL\Types\BooleanType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BoolType;



class ModalitesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pourcentage',IntegerType::class,['required'=>true], ['attr' => [
                'class' => 'id_pourcentage']])
            ->add('conditions', ChoiceType::class, ['required' => true,'placeholder'=>'',
                'choices'  => [
                    'date T1 atteinte' =>'date T1 atteinte',
                    'date T2 atteinte' =>'date T2 atteinte',
                    'date T3 atteinte' =>'date T3 atteinte',
                ]])
            ->add('description',TextareaType::class, array('required' => false))
            ->add('isapproved',ChoiceType::class,['required'=>false],[ "row_attr" => [
        "class" => "d-none"
    ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Modalites::class,
        ]);
    }
}
