<?php


namespace App\Form;


namespace App\Form;

use App\Entity\Cout;
use App\Entity\DateLone;
use App\Entity\Fournisseur;
use App\Entity\Paiement;
use App\Repository\PhaseRepository;
use App\Entity\Priorite;
use App\Entity\Projet;
use App\Entity\Phase;
use App\Entity\Risque;
use App\Entity\TypeBU;
use App\Form\CoutType;
use App\Form\ModalitesType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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

class PhasecType extends AbstractType
{
    private $phaseRepository;
    public function __construct(PhaseRepository $phaseRepository)
    {
        $this->phaseRepository = $phaseRepository;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('Phase', EntityType::class, [
                'required' => true,
                'class' => Phase::class,
                'choices' =>
                    $this->phaseRepository->reqbPhase(6,12,11)




                ,


                'attr' => [
                    'class' => 'phasec_Phase',

                ]
            ])

            ->add('garanti',IntegerType::class,['required'=>true], ['attr' => [
                'class' => 'phasec_garanti']])


            ->add('debit1bm', NumberType::class, ['required' => false,], ['attr' => [
                'class' => 'phasec_debit1bm']])
            ->add('debit2bm', NumberType::class, ['required' => false], ['attr' => [
                'class' => 'phasec_debit2bm']])
            ->add('debit3bm', NumberType::class, ['required' => false], ['attr' => [
                'class' => 'phasec_debit3bm']])
            ->add('debit4bm', NumberType::class, ['required' => false], ['attr' => [
                'class' => 'phasec_debit4bm']])

            //->add('DateLone')

            ->add('date1', DateType::class, [
                'label' => 'invoice_date',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('date2', DateType::class, [
                'label' => 'invoice_date',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('date3', DateType::class, [
                'label' => 'invoice_date',
                'widget' => 'single_text',
                'required' => true,
            ])

            ->add('datereel0', DateType::class, [
                'label' => 'invoice_date',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'phasec_datereel0',

                ]
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
                'attr' => [
                    'class' => 'phasec_couts',

                ]


            ))

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
            ->add('paiement',EntityType::class, [
                'required' => true,
                'class' => Paiement::class,
                'placeholder'=>'',
                'attr' => [
                    'class' => 'phasec_paiement'
                ]])

            ->add('choix10', ChoiceType::class, ['placeholder'=>'',
                    'mapped'=>false,
                    'attr' => [
                        'class' => 'phasec_choix10',

                    ],
                    'choices'  => [
                        'Oui' =>1,
                        'Non' =>2,
                    ]]

            )
        ;




    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,


        ]);
    }
}