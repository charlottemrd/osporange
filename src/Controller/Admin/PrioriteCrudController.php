<?php

namespace App\Controller\Admin;

use App\Entity\Priorite;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PrioriteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Priorite::class;
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
