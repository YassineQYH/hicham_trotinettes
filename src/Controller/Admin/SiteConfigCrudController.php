<?php

namespace App\Controller\Admin;

use App\Entity\SiteConfig;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{TextField, IdField, ChoiceField};
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Controller\Admin\AdminDashboardController;
use Doctrine\ORM\EntityManagerInterface;

class SiteConfigCrudController extends AbstractCrudController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return SiteConfig::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('name')
                ->setDisabled(true),

            ChoiceField::new('value', 'Maintenance active')
                ->setChoices([
                    'Désactivée' => '0',
                    'Activée' => '1',
                ])
                ->renderExpanded(true)
                ->allowMultipleChoices(false),
        ];
    }

    /**
     * 🔹 Méthode appelée après un update
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);

        if ($entityInstance instanceof SiteConfig) {
            $url = $this->adminUrlGenerator
                ->setDashboard(AdminDashboardController::class)
                ->generateUrl();

            // Redirection directe vers le dashboard
            header('Location: ' . $url);
            exit;
        }
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::updateEntity($entityManager, $entityInstance);

        if ($entityInstance instanceof SiteConfig) {
            $url = $this->adminUrlGenerator
                ->setDashboard(AdminDashboardController::class)
                ->generateUrl();

            header('Location: ' . $url);
            exit;
        }
    }
}
