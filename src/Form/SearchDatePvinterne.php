<?php


namespace App\Form;


use App\Entity\SearchDatePv;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
class SearchDatePvinterne extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('month', ChoiceType::class, [

                'placeholder'=>'',
                'required'=>false,
                'choices'  => [
                    '01' =>1,
                    '02' =>2,
                    '03' =>3,
                    '04' =>4,
                    '05' =>5,
                    '06' =>6,
                    '07' =>7,
                    '08' =>8,
                    '09' =>9,
                    '10' =>10,
                    '11' =>11,
                    '12' =>12,
                ],
            ])
            ->add('year', IntegerType::class,

                [
                    'required'=>false,
                    'attr' => [
                        'placeholder' => false,
                    ]

                ])




        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchDatePv::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }


}