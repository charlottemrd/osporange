<?php


namespace App\Form;

use App\Entity\Fournisseur;
use App\Entity\Phase;
use App\Entity\Priorite;
use App\Entity\Risque;
use App\Entity\Searchpv;
use App\Entity\TypeBU;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\SearchData;
use App\Repository\PvinternesRepository;
class SearchpvType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('ref', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rentrez une description',
                ]
            ])

            ->add('accept', ChoiceType::class, [

                'required'=>false,
                'placeholder'=>'',
                'choices'  => [
                    'Oui' =>true,
                    'Non' =>false]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Searchpv::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }


}