<?php

namespace App\Controller\Admin;

use App\Entity\Bilanmensuel;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BilanmensuelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bilanmensuel::class;
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
