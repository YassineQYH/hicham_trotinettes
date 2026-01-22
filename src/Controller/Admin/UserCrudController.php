<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Address;
use App\Form\AddressType;
use App\Form\UserAdminType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField,
    TextField,
    EmailField,
    ChoiceField,
    CollectionField,
    FormField
};
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserCrudController extends AbstractCrudController
{
    private MailerInterface $mailer;
    private UrlGeneratorInterface $router;

    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            ->setPageTitle('index', 'Gestion des utilisateurs')
            ->setDefaultSort(['id' => 'ASC'])
            ->setPaginatorPageSize(20);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::EDIT, fn(Action $action) => $action->setLabel('Modifier'))
            ->update(Crud::PAGE_INDEX, Action::DELETE, fn(Action $action) => $action->setLabel('Supprimer'))
            ->update(Crud::PAGE_INDEX, Action::DETAIL, fn(Action $action) => $action->setLabel('Voir'))
            ->update(Crud::PAGE_DETAIL, Action::EDIT, fn(Action $action) => $action->setLabel('Modifier'))
            ->update(Crud::PAGE_DETAIL, Action::DELETE, fn(Action $action) => $action->setLabel('Supprimer'));
    }

    public function configureFields(string $pageName): iterable
    {
        // ==========================
        // IDENTIFIANT
        // ==========================
        yield IdField::new('id')
            ->hideOnForm(); // Jamais modifiable


        // ==========================
        // INFORMATIONS UTILISATEUR
        // ==========================
        yield FormField::addPanel('Informations utilisateur');

        // Email → visible et éditable
        yield EmailField::new('email');

        // Prénom / Nom / Téléphone
        yield TextField::new('firstName')->setLabel('Prénom');
        yield TextField::new('lastName')->setLabel('Nom');
        yield TextField::new('tel')->setLabel('Tel');


        // ==========================
        // RÔLES
        // ==========================
        yield FormField::addPanel('Rôles');

        yield ChoiceField::new('roles')
            ->setLabel('Rôles')
            ->setChoices([
                'Utilisateur'     => 'ROLE_USER',
                'Administrateur'  => 'ROLE_ADMIN',
                'Super Admin'     => 'ROLE_SUPER_ADMIN',
            ])
            ->allowMultipleChoices()
            ->renderExpanded();


        // ==========================
        // ADRESSES
        // ==========================
        yield FormField::addPanel('Adresses');

        yield CollectionField::new('addresses', 'Adresses')
            ->setEntryType(AddressType::class)
            ->allowAdd()
            ->allowDelete()
            ->setFormTypeOption('by_reference', false)
            ->onlyOnForms();


        // ==========================
        // CHAMPS SENSIBLES / TECHNIQUES
        // ==========================
        // ⚠️ Ils existent en base mais NE DOIVENT JAMAIS
        // être générés dans le formulaire EasyAdmin
        yield TextField::new('password')
            ->hideOnForm()
            ->hideOnIndex()
            ->hideOnDetail();

        yield TextField::new('passwordResetToken')
            ->hideOnForm()
            ->hideOnIndex()
            ->hideOnDetail();

        yield TextField::new('passwordResetTokenExpiresAt')
            ->hideOnForm()
            ->hideOnIndex()
            ->hideOnDetail();
    }



    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            return;
        }

        // Générer un token unique pour création de mot de passe
        $token = Uuid::v4()->toRfc4122();
        $entityInstance->setPasswordResetToken($token);
        $entityInstance->setPasswordResetTokenExpiresAt((new \DateTime())->modify('+1 day'));

        $entityManager->persist($entityInstance);

        // Email
        $url = $this->router->generate(
            'app_set_password',
            ['token' => $entityInstance->getPasswordResetToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $email = (new Email())
            ->from('no-reply@mhvolt.com')
            ->to($entityInstance->getEmail())
            ->subject('Création de votre mot de passe')
            ->html(
                "Bonjour {$entityInstance->getFirstName()},<br><br>" .
                "Cliquez sur ce lien pour créer votre mot de passe : <a href='{$url}'>Créer mon mot de passe</a><br>" .
                "Ce lien expire dans 24h."
            );

        $this->mailer->send($email);

        // Flash
        $this->addFlash('success', 'Utilisateur créé avec succès. Email de création de mot de passe envoyé.');
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::updateEntity($entityManager, $entityInstance);
        $this->addFlash('success', 'Utilisateur modifié avec succès.');
    }
}
