<?php

namespace App\Form;
use App\Entity\Profil;
use App\Entity\Fournisseur;
use App\Entity\User;
use App\Form\ProfilType;
use App\Repository\FournisseurRepository;
use App\Repository\LdapRepository;
use LdapTools\Bundle\LdapToolsBundle\Form\Type\LdapObjectType;
use LdapTools\Event\Event;
use LdapTools\LdapManager;
use LdapTools\Query\LdapQueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\CallbackValidator;
use function PHPUnit\Framework\isInstanceOf;


class EditfournisseurType extends AbstractType
{

    private $fournisseurRepository;
    public function __construct( FournisseurRepository $fournisseurRepository)
    {
        $this->fournisseurRepository = $fournisseurRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('devise', TextType::class, ['required' => true,
                'attr' => [
                    'class' => 'fournisseur_devise'
                ]])
            ->add('adress')
            ->add('mail')
            ->add('phone')
            ->add('profils', CollectionType::class, array(
                'entry_type' => ProfilType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                "row_attr" => [
                    "class" => "d-none"
                ],
            ))
            ->add('interlocuteur', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class,[
                    'required'=>true,
                    'class'=>User::class,
                    'placeholder'=>'',
                    'attr'=>['class'=>'editfournisseur_interlocuteur']
                ]

            )


        ;



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //  'data_class' => Fournisseur::class,
            'data_class' => 'App\Entity\Fournisseur',


        ]);
    }
}
