<?php

namespace App\Form;

use App\Entity\Pvinternes;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormTypeInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Pvinterneform extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('pourcentage', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pvinternes::class,
        ]);
    }
}
