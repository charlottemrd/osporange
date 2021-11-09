<?php

namespace App\Controller\Admin;

use App\Entity\Idmonthbm;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class IdmonthbmCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Idmonthbm::class;
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
