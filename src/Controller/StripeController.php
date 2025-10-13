<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Trottinette;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(EntityManagerInterface $entityManager, Cart $panier, string $reference): RedirectResponse|JsonResponse
    {
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);

        if (!$order) {
            return new JsonResponse(['error' => 'order not found'], 404);
        }

        foreach ($order->getOrderDetails()->getValues() as $product) {
            $product_object = $entityManager->getRepository(Trottinette::class)->findOneBy(['name' => $product->getProduct()]);
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN."/uploads/".$product_object->getImage()],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        // Ajouter les frais de livraison
        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice() * 100,
                'product_data' => [
                    'name' => 'Livraison',
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];

        Stripe::setApiKey('sk_test_51KNdRaBMBArCOnoiBGyovclE3rWKPO9X8dngKjHXezHj9SXaWeC3HrqOz7LCZAtXpVrJQzbx3PBPucDocAP8anBu00ZjyOIrSx');

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => $product_for_stripe,
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        return $this->redirect($checkout_session->url);
    }
}
