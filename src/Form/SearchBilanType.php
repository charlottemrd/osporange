<?php


namespace App\Form;

use App\Entity\Fournisseur;
use App\Entity\Phase;
use App\Entity\Priorite;
use App\Entity\Risque;
use App\Entity\SearchBilanmensuel;
use App\Entity\TypeBU;
use DateTime;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\SearchData;


use App\Entity\Cout;
use App\Entity\Bilanmensuel;
use App\Entity\Paiement;
use App\Entity\Projet;
use App\Entity\User;
use App\Form\CoutType;
use App\Form\ModalitesType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
class SearchBilanType extends AbstractType
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

            ->add('accept', ChoiceType::class, [

                'required'=>false,
                'placeholder'=>'',
                'choices'  => [
                    'Oui' =>true,
                    'Non' =>false]
            ])



        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchBilanmensuel::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }


}