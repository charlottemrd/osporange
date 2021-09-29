<?php


namespace App\Form;


namespace App\Form;

use App\Entity\Cout;
use App\Entity\Fournisseur;
use App\Entity\Paiement;
use App\Repository\PhaseRepository;
use App\Entity\Priorite;
use App\Entity\Projet;
use App\Entity\Phase;
use App\Entity\Risque;
use App\Entity\User;
use App\Entity\TypeBU;
use App\Form\CoutType;
use App\Form\ModalitesType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;


use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PhaseaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('Phase', EntityType::class, [
                'required' => true,
                'class' => Phase::class,
                'query_builder' => function (PhaseRepository $er)  {
                    return $er->createQueryBuilder('t')
                        ->andWhere('t.id >:rank')
                       ->setParameter('rank', 3);},
                'attr' => [
                    'class' => 'phasea_Phase'
                ]
            ])


            ->add('paiement', EntityType::class, [
                'required' => true,
                'class' => Paiement::class,
                'placeholder' => '',
                'attr' => [
                    'class' => 'phasea_paiement'
                ]])
            ->add('datel1', DateType::class,
                [
                    'label' => 'invoice_date',
                    'widget' => 'single_text',
                    'required' => true,


                ])
            ->add('date0', DateType::class, [
                'label' => 'invoice_date',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('date1', DateType::class, [
                'label' => 'invoice_date',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('date2', DateType::class, [
                'label' => 'invoice_date',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('date3', DateType::class, [
                'label' => 'invoice_date',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('datespec', DateType::class, [
                'label' => 'invoice_date',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('couts', CollectionType::class, array(
                'entry_type' => CoutType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ))
            ->add('modalites', CollectionType::class, array(
                'entry_type' => ModalitesType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'attr' => [
                    'class' => 'phasea_modalites'
                ],
                "row_attr" => [
                    "class" => "d-none"
                ],
            ));




    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,


        ]);
    }
}