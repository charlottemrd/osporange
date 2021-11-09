<?php

namespace App\Controller\Admin;

use App\Entity\Projet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProjetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Projet::class;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            'reference',['label_attr'=>['placeholder'=>'xzs']],
            'Fournisseur',
            'domaine'=>'Domaine',
            'sdomaine'=>'Sous domaine',
            'description'=>'Description',
            'taux'=>'Avancement',
            'isplanningrespecte'=>'Respect du planning',

        ];
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
