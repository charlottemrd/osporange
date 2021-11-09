<?php

namespace App\Controller\Admin;

use App\Entity\Modalites;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ModalitesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Modalites::class;
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
