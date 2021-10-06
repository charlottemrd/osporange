<?php

namespace App\Form;

use App\Entity\Commentaire;
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
class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('reference',TextType::class, [
                'label' => false,
                'required' => true,
                'disabled'=>true,


            ])

            ->add('domaine',TextType::class, [
                'label' => false,
                'required' => true,

            ])

            ->add('sdomaine',TextType::class, [
                'label' => false,
                'required' => true,
            ])

            ->add('description',TextareaType::class, [
                'label' => false,
                'required' => true,
            ])

            ->add('Phase', EntityType::class, [
                'required' => true,
                'class' => Phase::class,
                'placeholder'=>'',
                'attr' => [
                    'class' => 'projet_Phase'
                ]
            ])



            ->add('fournisseur', EntityType::class, [
                'required' => true,
                'class' => Fournisseur::class,
                'placeholder'=>'',
                'attr' => [
                    'class' => 'projet_fournisseur'
                ]

            ])







            ->add('typebu', EntityType::class, [
                'required' => true,
                'class' => TypeBU::class,
                'placeholder'=>'',
                'attr' => [
                    'class' => 'projet_typebu'
                ]

            ])

            ->add('priorite', EntityType::class, [
                'required' => false,
                'class' => Priorite::class,
                'placeholder'=>'',

            ])

            ->add('risque', EntityType::class, [
                'required' => false,
                'class' => Risque::class,
                'placeholder'=>'',
            ])
            ->add('paiement',EntityType::class, [
                'required' => true,
                'class' => Paiement::class,
                'placeholder'=>'',
                'attr' => [
                    'class' => 'projet_paiement'
                ]])


            ->add('datel1', DateType::class,
  [
                'label'=>'invoice_date',
                'widget'=>'single_text',
                'required'=>true,


            ])

            ->add('date0', DateType::class, [
                'label'=>'invoice_date',
                'widget'=>'single_text',
                'required'=>true,
            ])

            ->add('date1', DateType::class, [
                'label'=>'invoice_date',
                'widget'=>'single_text',
                'required'=>true,
            ])

            ->add('date2', DateType::class, [
                'label'=>'invoice_date',
                'widget'=>'single_text',
                'required'=>true,
            ])


            ->add('date3', DateType::class, [
                'label'=>'invoice_date',
                'widget'=>'single_text',
                'required'=>true,
            ])


            ->add('datespec', DateType::class, [
                'label'=>'invoice_date',
                'widget'=>'single_text',
                'required'=>true,
            ])

            ->add('couts', CollectionType::class, array(
                'entry_type'   => CoutType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype'=> true,


            ))

              ->add('modalites', CollectionType::class, array(
                  'entry_type' => ModalitesType::class,
                  'allow_add' => true,
                  'allow_delete' => true,
                  'by_reference' => false,
                  'prototype' => true,
                  'attr' => [
                      'class' => 'projet_modalites'
                  ],
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

        ;

    ;


        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event) {
            $user = $event->getData()->getUser();


            $event->getForm()->add('user', EntityType::class, array('disabled' => ($user !== null), 'required' => true,
                'class' => User::class,
                'placeholder' => ''));
        });


















    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,





        ]);
    }
}