<?php

namespace App\Controller\Admin;

use App\Entity\Illustrationaccess;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, ImageField, AssociationField};

class IllustrationaccessCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Illustrationaccess::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ImageField::new('image')->setUploadDir('public/uploads/illustrationaccess')->setBasePath('/uploads/illustrationaccess'),
            AssociationField::new('accessory'),
        ];
    }
}
