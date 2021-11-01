<?php

namespace App\Validator;

use App\Entity\Bilanmensuel;
use App\Entity\DataTrois;
use App\Entity\Fournisseur;
use App\Repository\FournisseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidbilanValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\Validbilan */


        $valuep = $value->getProjet;

        if ($valuep != null) {

            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $valuep)
                ->addViolation();
        }
    }
}
