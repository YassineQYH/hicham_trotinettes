<?php

namespace App\Controller\Admin;

use App\Entity\TrottinetteCaracteristique;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField,
    AssociationField,
    TextField
};

class TrottinetteCaracteristiqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TrottinetteCaracteristique::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // Titre de la page
            ->setEntityLabelInSingular('Caractéristique de trottinette')
            ->setEntityLabelInPlural('Caractéristiques des trottinettes')

            // Traductions des boutons
            ->setPageTitle(Crud::PAGE_INDEX, 'Caractéristiques des trottinettes')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter une caractéristique')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier la caractéristique')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Détails de la caractéristique')

            ->setFormThemes(['@EasyAdmin/crud/form_theme.html.twig']); // si tu veux surcharger le thème du formulaire
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            AssociationField::new('trottinette', 'Trottinette')
                ->setRequired(true),

            AssociationField::new('caracteristique', 'Caractéristique')
                ->setRequired(true),

            TextField::new('value', 'Valeur')
                ->setRequired(true),
        ];
    }
}
