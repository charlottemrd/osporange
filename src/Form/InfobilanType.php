<?php

namespace App\Form;

use App\Entity\Infobilan;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class InfobilanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event) {

            $nombreprofit = $event->getData()->getnombreprofit();


            $event->getForm()

                ->add('nombreprofit', IntegerType::class,array('disabled' => ($nombreprofit !== null),'required' => true


                ))

            ;
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Infobilan::class,
        ]);
    }
}
