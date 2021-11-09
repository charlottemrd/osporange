<?php

namespace App\Controller\Admin;

use App\Entity\DateOnePlus;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DateOnePlusCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DateOnePlus::class;
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
