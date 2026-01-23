<?php

namespace App\Controller\Admin;

use App\Entity\Caracteristique;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField,
    TextField,
    AssociationField
};

class CaracteristiqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Caracteristique::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Caractéristique')
            ->setEntityLabelInPlural('Caractéristiques');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('name', 'Nom'),

            AssociationField::new('categorie', 'Catégorie')
                ->setRequired(true),
        ];
    }
}
