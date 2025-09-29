<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, EmailField, ArrayField, PasswordField
};

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email'),
            ArrayField::new('roles'),
            PasswordField::new('password')->onlyOnForms(),
            TextField::new('firstName'),
            TextField::new('lastName'),
            TextField::new('tel'),
            TextField::new('country'),
            TextField::new('postalCode'),
            TextField::new('address'),
        ];
    }
}
