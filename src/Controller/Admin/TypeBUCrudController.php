<?php

namespace App\Controller\Admin;

use App\Entity\TypeBU;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TypeBUCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeBU::class;
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
