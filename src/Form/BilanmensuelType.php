<?php

namespace App\Form;

use App\Entity\Bilanmensuel;
use App\Entity\Fournisseur;
use App\Entity\Paiement;
use App\Entity\Phase;
use App\Entity\Projet;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class BilanmensuelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('Infobilans', CollectionType::class, array(
                'entry_type'   => InfobilanType::class,
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

            $projet = $event->getData()->getProjet();


            $event->getForm()

                ->add('projet', EntityType::class,array('disabled' => ($projet !== null), 'required' => true,
                    'class' =>Projet::class,'attr' => array(
                        'class' => 'select23'),
                    'placeholder' => ''))

            ;
        });













    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bilanmensuel::class,
        ]);
    }
}
