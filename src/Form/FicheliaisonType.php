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
class FicheliaisonType extends AbstractType
{
    public function  __construct()
    {
        $this->dateemis = new \DateTime();
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('reference',TextType::class, [
                'label' => false,
                'required' => true,
            ])
            ->add('priorite', EntityType::class, [
                'required' => true,
                'class' => Priorite::class,
                'label' => false,
                'required' => true,
            ])

            ->add('dateemis', DateType::class, array(
                'data' => new \DateTime(),
                'widget' => 'single_text',
                'mapped'=>false,
                'label' => false,
                'required' => true,
            ))


            ->add('emetteur',TextType::class,[
                'label' => false,
                'required' => true,
                'mapped'=>false
            ])


            ->add('sujet',TextType::class,[
                'label' => false,
                'required' => true,
                'mapped'=>false
            ])
            ->add('description',TextareaType::class,[
                'label' => false,
                'required' => true,
                'mapped'=>false
            ])
            ->add('piecejointes',TextareaType::class,[
                'label' => false,
                'required' => false,
                'mapped'=>false
            ])

        ;













    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,





        ]);
    }
}