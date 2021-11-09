<?php

namespace App\Controller\Admin;

use App\Entity\Risque;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RisqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Risque::class;
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
