<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\Modalites;
use App\Entity\Fournisseur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;



use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\ZeroComparisonConstraintTrait;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
class PacType extends AbstractType
{
    public function  __construct()
    {
        $this->dateemis = new \DateTime();
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('objet', TextType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false])
            ->add('refpv', TextType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false])
            ->add('datepv', DateType::class, array(
                'data' => new \DateTime(),
                'widget' => 'single_text',
                'mapped' => false,
                'label' => false,
                'required' => true,
            ))
            ->add('refcontrat', TextType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false])
            ->add('facture', TextType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false])
            ->add('refcontratsap', TextType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false])
            ->add('boncommande', TextType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false])
            ->add('datedebut', DateType::class, array(
                'data' => new \DateTime(),
                'widget' => 'single_text',
                'label' => false,
                'required' => true,
            ))
            ->add('datefin', DateType::class, array(
                'data' => new \DateTime(),
                'widget' => 'single_text',
                'label' => false,
                'required' => true,
            ))
            ->add('reserve1', TextAreaType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false])

            ->add('conditions', TextType::class, [
                'label' => false,
                'required' => true,
            ])
            ->add('pourcentage', TextType::class, [
                'label' => false,
                'required' => true,
            ])
            ->add('nomdesignation', TextType::class, [
                'label' => false,
                'required' => false,
                'mapped'=>false
            ])
            ->add('qttdesignation', TextType::class,[
                'label' => false,
                'required' =>false,
                'mapped'=>false
            ])

            ->add('penalites', ChoiceType::class,[

                'expanded'=>true,
                'multiple'=>false,
                'mapped'=>false,
                'label' => false,
                "row_attr" => [
                    "class" => "pac_penalites"
                ],
                'choices'  => [
                    'Oui' =>'0',
                    'Non' =>'1',
                ]])
            ->add('retard',CheckboxType::class,[
                'mapped'=>false,
                'label'=>'Retard dans les délais d’exécution / Livraison',
                'required' => false,
                'attr' => [
                    "class" => "pac_retard"
                ],
            ])
            ->add('retardmontant', TextType::class,[
                'label' => false,
                'required' => false,
                'mapped'=>false,
                'attr' => [
                    "class" => "pac_retardmontant"
                ],
            ])
            ->add('respect',CheckboxType::class,[
                'mapped'=>false,
                'required' => false,
                'label'=>'Non-respect des SLA & Obligations techniques',
                'attr' => [
                    "class" => "pac_respect"
                ],
            ])
            ->add('respectmontant', TextType::class,[
                'label' => false,
                'required' => false,
                'mapped'=>false,
                'attr' => [
                    "class" => "pac_respectmontant"
                ],
            ])
            ->add('degat',CheckboxType::class,[
                'mapped'=>false,
                'label' => false,
                'required' => false,
                'label'=>'Dégâts',
                'attr' => [
                    "class" => "pac_degat"
                ],
            ])
            ->add('degatmontant', TextType::class,[
                'label' => false,
                'required' => false,
                'mapped'=>false,
                'attr' => [
                    "class" => "pac_degatmontant"
                ],
            ])
            ->add('qualite',CheckboxType::class,[
                'mapped'=>false,
                'label'=>'Qualité de la prestation / marchandise',
                'required' => false,
                'attr' => [
                    "class" => "pac_qualite"
                ],
            ])
            ->add('qualitemontant', TextType::class,[
                'label' => false,
                'required' => false,
                'mapped'=>false,
                'attr' => [
                    "class" => "pac_qualitemontant"
                ],
            ])
            ->add('retardfact',CheckboxType::class,[
                'mapped'=>false,
                'label'=>'Retard dans les délais de facturation',
                'required' => false,
                'attr' => [
                    "class" => "pac_retardfact"
                ],
            ])
            ->add('retardfactmontant', TextType::class,[
                'label' => false,
                'required' => false,
                'mapped'=>false,
                'attr' => [
                    "class" => "pac_retardfactmontant"
                ],
            ])
            ->add('autre',CheckboxType::class,[
                'mapped'=>false,
                'label'=>false,
                'required' => false,
                'attr' => [
                    "class" => "pac_autre"
                ],
            ])
            ->add('autremontant', TextType::class,[
                'label' => false,
                'required' => false,
                'mapped'=>false,
                'attr' => [
                    "class" => "pac_autremontant"
                ],
            ])
            ->add('autredesc', TextType::class, [
                'label' => 'Autres :',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    "class" => "pac_autredesc"
                ],])

            ->add('signataire', TextType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false])

            ->add('rolesignataire', TextType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false])

            ->add('datesignature', DateType::class, array(
                'data' => new \DateTime(),
                'widget' => 'single_text',
                'mapped' => false,
                'label' => false,
                'required' => false,
            ))

        ;

    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Modalites::class,





        ]);
    }
}