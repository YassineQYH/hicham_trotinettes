<?php

// src/Controller/Admin/SiteConfigCrudController.php

namespace App\Controller\Admin;

use App\Entity\SiteConfig;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{TextField, BooleanField, IdField, ChoiceField};

class SiteConfigCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SiteConfig::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('name')
                ->setDisabled(true), // 🔒 on empêche toute modif du nom

            ChoiceField::new('value', 'Maintenance active')
                ->setChoices([
                    'Désactivée' => '0',
                    'Activée' => '1',
                ])
                ->renderExpanded(true)
                ->allowMultipleChoices(false),
        ];
    }
}
