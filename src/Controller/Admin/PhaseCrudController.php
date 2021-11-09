<?php

namespace App\Controller\Admin;

use App\Entity\Phase;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PhaseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Phase::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
