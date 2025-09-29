<?php

namespace App\Controller\Admin;

use App\Entity\Trottinette;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, TextField, TextEditorField, BooleanField, ImageField, CollectionField, AssociationField};

class TrottinetteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trottinette::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('name'),
            TextField::new('nameShort'),
            TextField::new('slug'),

            TextEditorField::new('description'),
            TextEditorField::new('descriptionShort'),

            ImageField::new('image')
                ->setUploadDir('public/uploads/trottinettes')
                ->setBasePath('/uploads/trottinettes')
                ->setRequired(false),

            BooleanField::new('isBest'),
            BooleanField::new('isHeader'),
            ImageField::new('headerImage')
                ->setUploadDir('public/uploads/trottinettes')
                ->setBasePath('/uploads/trottinettes')
                ->setRequired(false),

            TextField::new('headerBtnTitle'),
            TextField::new('headerBtnUrl'),

            AssociationField::new('accessories'),
            CollectionField::new('trottinetteCaracteristiques')
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(\App\Form\TrottinetteCaracteristiqueType::class)
                ->setFormTypeOption('by_reference', false),

            CollectionField::new('descriptionSections')
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(\App\Form\TrottinetteDescriptionSectionType::class)
                ->setFormTypeOption('by_reference', false),
        ];
    }
}
