<?php

namespace App\Controller\Admin;

use App\Entity\Cout;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CoutCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cout::class;
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
