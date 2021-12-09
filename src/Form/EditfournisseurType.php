<?php

namespace App\Form;
use App\Entity\Profil;
use App\Entity\Fournisseur;
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
    private $ldapRepository;
    private $ldapManager;
    public function __construct(LdapManager $ldapManager , FournisseurRepository $fournisseurRepository,LdapRepository $ldapRepository)
    {
        $this->fournisseurRepository = $fournisseurRepository;
        $this->ldapRepository = $ldapRepository;
        $this->ldapManager = $ldapManager;
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
            ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $interlocuteurfullname = $event->getData()->getFournisseurfullname();

            $event->getForm()->add('fournisseurfullname', TextType::class, array('disabled' => ($interlocuteurfullname !== null), 'required' => true,
            ));


        });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //  'data_class' => Fournisseur::class,
            'data_class' => 'App\Entity\Fournisseur',


        ]);
    }
}
