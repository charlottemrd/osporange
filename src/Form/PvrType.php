<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\Pvinternes;
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
class PvrType extends AbstractType
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
                'mapped' => false,
            ))
            ->add('datefin', DateType::class, array(
                'data' => new \DateTime(),
                'widget' => 'single_text',
                'label' => false,
                'required' => true,
                'mapped' => false,
            ))
            ->add('reservemineure', TextAreaType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false])
            ->add('reservemajeure', TextAreaType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false])

            ->add('conditions', TextType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false,
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
            ->add('nomdesignation2', TextType::class, [
                'label' => false,
                'required' => false,
                'mapped'=>false
            ])
            ->add('qttdesignation2', TextType::class,[
                'label' => false,
                'required' =>false,
                'mapped'=>false
            ])
            ->add('bonapayer', ChoiceType::class,[
                'expanded'=>true,
                'multiple'=>false,
                'mapped'=>false,
                'label' => false,
                "attr" => [
                    "class" => "pvr_bonapayer"
                ],
                "label_attr" => [
                    "class" => "pvr_labelbonapayer"
                ],
                'choices'  => [
                    'Oui' =>'0',
                    'Non' =>'1',
                ]])



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
            'data_class' => Pvinternes::class,





        ]);
    }
}