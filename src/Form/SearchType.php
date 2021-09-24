<?php


namespace App\Form;

use App\Entity\Fournisseur;
use App\Entity\Phase;
use App\Entity\Priorite;
use App\Entity\Risque;
use App\Entity\TypeBU;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\SearchData;
class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rentrez une description',

                ]
            ])

            ->add('ref', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rentrez une référence',
                ]
            ])


            ->add('domain', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rentrez un domaine',
                ]
            ])

            ->add('sdomain', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rentrez un sous-domaine'
                ]
            ])


                    ->add('fournisseurs', EntityType::class, [
                           'label' => false,
                           'required' => false,
                        'class' => Fournisseur::class,
                        'expanded' => false,
                        'multiple' => true,

                        'attr' => [
                            'class' => 'js-example-basic-single',
                            'placeholder' => 'Rentrez un sous-domaine'
                        ]

                       ])




            ->add('phases', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Phase::class,
                'expanded' => false,
                'multiple' => true,

                'attr' => [
                    'class' => 'js-example-basic-single',
                ]
            ])

            ->add('risques', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Risque::class,
                'expanded' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'js-example-basic-single',
                ]
            ])

            ->add('priority', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Priorite::class,
                'expanded' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'js-example-basic-single',
                ]
            ])

            ->add('bu', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => TypeBU::class,
                'expanded' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'js-example-basic-single',
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }


}