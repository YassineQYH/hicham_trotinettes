<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class OrderCancelController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/erreur/{stripeSessionId}', name: 'order_cancel')]
    public function index(string $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)
            ->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() !== $this->getUser()) {
            // Si la commande n'existe pas ou n'appartient pas à l'utilisateur connecté, redirection vers l'accueil
            return $this->redirectToRoute('home');
        }

        // Ici, tu peux ajouter l'envoi d'email pour notifier l'utilisateur de l'échec du paiement

        return $this->render('order_cancel/index.html.twig', [
            'order' => $order,
        ]);
    }
}
