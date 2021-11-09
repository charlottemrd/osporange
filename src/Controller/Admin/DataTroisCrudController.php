<?php

namespace App\Controller\Admin;

use App\Entity\DataTrois;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DataTroisCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DataTrois::class;
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
