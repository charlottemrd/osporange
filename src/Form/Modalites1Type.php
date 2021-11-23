<?php

namespace App\Form;

use App\Entity\Modalites;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Modalites1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('pourcentage')
            ->add('datedebut')
            ->add('datefin')
            ->add('conditionsatisfield')
            ->add('conditions')
            ->add('isapproved')
            ->add('isencours')
            ->add('rank')
            ->add('decisionsapproved')
            ->add('projet')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Modalites::class,
        ]);
    }
}
