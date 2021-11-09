<?php

namespace App\Controller\Admin;

use App\Entity\Infobilan;
use App\Entity\Bilanmensuel;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class InfobilanCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Infobilan::class;
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
