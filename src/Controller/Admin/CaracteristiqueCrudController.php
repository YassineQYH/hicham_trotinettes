<?php

namespace App\Controller\Admin;

use App\Entity\Caracteristique;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, TextField, CollectionField};

class CaracteristiqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Caracteristique::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            CollectionField::new('trottinetteCaracteristiques')
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(\App\Form\TrottinetteCaracteristiqueType::class)
                ->setFormTypeOption('by_reference', false),
        ];
    }
}
