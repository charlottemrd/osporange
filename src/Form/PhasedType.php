<?php


namespace App\Form;


namespace App\Form;

use App\Entity\Cout;
use App\Entity\DateLone;
use App\Entity\Fournisseur;
use App\Entity\Paiement;
use App\Repository\PhaseRepository;
use App\Entity\Priorite;
use App\Entity\Projet;
use App\Entity\Phase;
use App\Entity\Risque;
use App\Entity\TypeBU;
use App\Form\CoutType;
use App\Form\ModalitesType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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

class PhasedType extends AbstractType
{
    private $phaseRepository;
    public function __construct(PhaseRepository $phaseRepository)
    {
        $this->phaseRepository = $phaseRepository;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('Phase', EntityType::class, [
                'required' => true,
                'class' => Phase::class,
                'choices' =>
                    $this->phaseRepository->reqbPhase(7,12,11)




                ,


                'attr' => [
                    'class' => 'phased_Phase',

                ]
            ])



            //->add('DateLone')


            ->add('datereel1', DateType::class, [
                'label' => 'invoice_date',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'phased_datereel1',

                ]
            ])


            ->add('choix11', ChoiceType::class, ['placeholder'=>'',
                    'mapped'=>false,
                    'attr' => [
                        'class' => 'phased_choix11',

                    ],
                    'choices'  => [
                        'Oui' =>1,
                        'Non' =>2,
                    ]]

            )






        ;




    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,


        ]);
    }
}