<?php

namespace App\Form;

use App\Entity\Cout;
use App\Entity\Fournisseur;
use App\Entity\Paiement;
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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
class ModifydfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('reference',TextType::class, [
                'label' => false,
                'required' => true,
            ])


            ->add('description',TextareaType::class, [
                'label' => false,
                'required' => false,
            ])

            ->add('priorite', EntityType::class, [
                'required' => false,
                'class' => Priorite::class,
                'placeholder'=>'',


            ])
            ->add('garanti',IntegerType::class,['required'=>true], ['attr' => [
                'class' => 'modifydf_garanti']])

            ->add('debit1bm',IntegerType::class,['required'=>false,], ['attr' => [
                'class' => 'modifydf_debit1bm']])
            ->add('debit2bm',IntegerType::class,['required'=>false], ['attr' => [
                'class' => 'modifydf_debit2bm']])
            ->add('debit3bm',IntegerType::class,['required'=>false], ['attr' => [
                'class' => 'modifydf_debit3bm']])

            ->add('taux',IntegerType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'modifydf_taux'
                ]
            ])

            ->add('datereel0', DateType::class,
                [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'attr' => [
                        'class' => 'modifydf_datereel0'
                    ]

                ])


            ->add('datereel1', DateType::class,
                [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'attr' => [
                        'class' => 'modifydf_datereel1'
                    ]

                ])

            ->add('datereel2', DateType::class,
                [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'attr' => [
                        'class' => 'modifydf_datereel2'
                    ]

                ])

            ->add('date3', DateType::class,
                [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'attr' => [
                        'class' => 'modifydf_date3'
                    ]

                ])

            ->add('datereell1', DateType::class,
                [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'attr' => [
                        'class' => 'modifydf_datereell1'
                    ]

                ])

            ->add('isplanningrespecte', ChoiceType::class,[
                'choices'  => [
                    'Le planning n\'est pas respecté' =>'0',
                    'Le planning est respecté' =>'1',
                ]])

            ->add('risque', EntityType::class, [
                'required' => false,
                'class' => Risque::class,
                'placeholder'=>'',
            ])
            ->add('datespec', DateType::class, [
                'label' => 'invoice_date',
                'widget' => 'single_text',
                'required' => true,
            ])

            ->add('modalites', CollectionType::class, array(
                'entry_type' => ModalitesType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                "row_attr" => [
                    "class" => "d-none"
                ],
            ))

            ->add('commentaires', CollectionType::class, [
                'entry_type' => CommentaireType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                "row_attr" => [
                    "class" => "d-none"
                ],
            ])

            ->add('couts', CollectionType::class, array(
                'entry_type'   => CoutType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype'=> true,
                "row_attr" => [
                    "class" => "d-none"
                ],


            ))




        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event) {
            $user = $event->getData()->getFullnamechefprojet();
            $fournisseur = $event->getData()->getFournisseur();
            $phase = $event->getData()->getPhase();
            $domaine = $event->getData()->getDomaine();
            $sdomaine = $event->getData()->getSDomaine();
            $typebu = $event->getData()->getTypebu();
            $paiement=$event->getData()->getPaiement();

            $event->getForm()

                ->add(
                    'fullnamechefprojet', TextType::class, array('disabled' => ($user !== null), 'required' => true,
                ))

                ->add('fournisseur', EntityType::class,array('disabled' => ($fournisseur !== null), 'required' => true,
                    'class' =>Fournisseur::class,
                    'placeholder' => ''))
                ->add('Phase', EntityType::class,array('disabled' => ($phase !== null), 'required' => true,
                    'class' =>Phase::class,
                    'placeholder' => ''))
                ->add('domaine', TextType::class,array('disabled' => ($domaine !== null), 'required' => true,
                ))
                ->add('sdomaine', TextType::class,array('disabled' => ($sdomaine !== null),'required' => true,
                ))
                ->add('typebu', EntityType::class,array('disabled' => ($typebu !== null), 'required' => true,
                    'class' =>TypeBU::class,
                    'placeholder' => ''))
                ->add('paiement', EntityType::class,array('disabled' => ($paiement !== null), 'required' => true,
                    'class' =>Paiement::class,
                    'placeholder' => ''))
            ;
        });















    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,





        ]);
    }
}