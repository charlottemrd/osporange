<?php

namespace App\Form;

use App\Entity\Cout;
use App\Entity\Profil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class CoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreprofil')
            ->add('profil', EntityType::class,[
                'class' => Profil::class,
                'attr' => array(
                'class' => 'select23',
    )]
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cout::class,
        ]);
    }
}
