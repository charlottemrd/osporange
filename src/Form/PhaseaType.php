<?php


namespace App\Form;


namespace App\Form;

use App\Entity\Cout;
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

class PhaseaType extends AbstractType
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
                    $this->phaseRepository->reqaPhase(5,4,12,11)

                        ,








                'attr' => [
                    'class' => 'phasea_Phase',

                ]
            ])



            ->add('datel1', DateType::class,
                [
                    'label' => 'invoice_date',
                    'widget' => 'single_text',
                    'required' => true,


                ])
            ->add('date0', DateType::class, [
                'label' => 'invoice_date',
                'widget' => 'single_text',
                'required' => false,
            ])



            ;




    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,


        ]);
    }
}