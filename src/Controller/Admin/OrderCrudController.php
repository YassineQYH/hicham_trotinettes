<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, ArrayField, MoneyField, ChoiceField, DateTimeField, TextEditorField
};
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    private EntityManagerInterface $entityManager;
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updatePreparation', 'Préparation en cours', 'fas fa-box-open')
            ->linkToCrudAction('updatePreparation')
            ->displayIf(static fn($order) => $order->getDeliveryState() === 0)
            ->setHtmlAttributes(['data-id' => 'entity.id']);

        $updateDelivery = Action::new('updateDelivery', 'Livraison en cours', 'fas fa-truck')
            ->linkToCrudAction('updateDelivery')
            ->displayIf(static fn($order) => $order->getDeliveryState() === 1)
            ->setHtmlAttributes(['data-id' => 'entity.id']);

        return $actions
            ->add(Crud::PAGE_DETAIL, $updatePreparation)
            ->add(Crud::PAGE_DETAIL, $updateDelivery)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    private function handleOrderState(Order $order, int $deliveryState, string $message)
    {
        $order->setDeliveryState($deliveryState);
        $this->entityManager->flush();

        $this->addFlash('notice', $message);

        $mail = new Mail();
        $content = "Bonjour " . $order->getUser()->getFirstName() . "<br>Hich'Trott vous informe que votre commande n°<strong>"
            . $order->getReference() . "</strong> est " . $message;
        $mail->send(
            $order->getUser()->getEmail(),
            $order->getUser()->getFirstName(),
            "Votre commande " . $order->getReference(),
            $content
        );
    }

    public function updatePreparation(AdminContext $context)
    {
        $orderId = $context->getRequest()->query->get('entityId');
        $order = $this->entityManager->getRepository(Order::class)->find($orderId);

        if (!$order) {
            $this->addFlash('danger', 'Commande introuvable.');
            return $this->redirect($this->adminUrlGenerator->setController(self::class)->setAction('index')->generateUrl());
        }

        $this->handleOrderState($order, 1, '<u>en cours de préparation</u>');

        return $this->redirect($this->adminUrlGenerator
            ->setController(self::class)
            ->setAction('detail')
            ->setEntityId($order->getId())
            ->generateUrl());
    }

    public function updateDelivery(AdminContext $context)
    {
        $orderId = $context->getRequest()->query->get('entityId');
        $order = $this->entityManager->getRepository(Order::class)->find($orderId);

        if (!$order) {
            $this->addFlash('danger', 'Commande introuvable.');
            return $this->redirect($this->adminUrlGenerator->setController(self::class)->setAction('index')->generateUrl());
        }

        $this->handleOrderState($order, 2, '<u>en cours de livraison</u>');

        return $this->redirect($this->adminUrlGenerator
            ->setController(self::class)
            ->setAction('detail')
            ->setEntityId($order->getId())
            ->generateUrl());
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            DateTimeField::new('createdAt', 'Passée le'),

            TextField::new('user', 'Utilisateur')
                ->onlyOnDetail()
                ->formatValue(fn($value, $entity) => (string) $entity->getUser()),

            TextEditorField::new('delivery', 'Adresse de livraison')->onlyOnDetail(),
            MoneyField::new('total', 'Total produit')->setCurrency('EUR')->setStoredAsCents(false),
            MoneyField::new('carrierPrice', 'Frais de livraison')->setCurrency('EUR')->setStoredAsCents(false),

            ChoiceField::new('paymentState', 'Paiement')
                ->setChoices([
                    'Non payée' => 0,
                    'Payée' => 1,
                ])
                ->renderAsBadges([
                    0 => 'danger',
                    1 => 'success',
                ]),

            ChoiceField::new('deliveryState', 'Traitement')
                ->setChoices([
                    'Commande en attente' => 0,
                    'Préparation en cours' => 1,
                    'Livraison en cours' => 2,
                    'Livraison terminée' => 3,
                ])
                ->renderAsBadges([
                    0 => 'secondary',
                    1 => 'warning',
                    2 => 'info',
                    3 => 'success',
                ]),

            // 🟢 Transporteur & suivi - maintenant éditables
            TextField::new('carrier', 'Transporteur principal')->onlyOnDetail()->setFormTypeOption('disabled', false),
            TextField::new('trackingNumber', 'Numéro de suivi principal')->onlyOnDetail()->setFormTypeOption('disabled', false),
            TextField::new('secondaryCarrierTrackingNumber', 'Numéro de suivi secondaire')->onlyOnDetail()->setFormTypeOption('disabled', false),

            // 🟢 Produits achetés
            ArrayField::new('orderDetails', 'Produits achetés')
                ->setTemplatePath('admin/fields/order_details.html.twig')
                ->onlyOnDetail(),
        ];
    }
}
