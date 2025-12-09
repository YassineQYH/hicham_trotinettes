<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Trottinette;
use App\Entity\Accessory;
use App\Entity\Promotion;
use App\Service\PromotionService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(
        EntityManagerInterface $entityManager,
        Cart $panier,
        RequestStack $requestStack,
        PromotionService $promotionService,
        string $reference
    ): RedirectResponse {

        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('login_required', 'Vous devez être connecté pour procéder au paiement.');
            return $this->redirectToRoute('cart');
        }

        // 🔍 Récupération de la commande
        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        if (!$order) {
            $this->addFlash('error', 'Commande introuvable.');
            return $this->redirectToRoute('cart');
        }

        // 🔑 Lecture du code promo en session
        $session = $requestStack->getSession();
        $promoCode = $session->get('promo_code');
        $promo = $promoCode ? $entityManager->getRepository(Promotion::class)->findOneBy(['code' => $promoCode]) : null;

        // 🔹 Construction des lignes Stripe
        $product_for_stripe = [];

        // 1️⃣ Produits
        foreach ($order->getOrderDetails() as $item) {
            // Récupération du produit pour l'image
            $product_object = $entityManager->getRepository(Trottinette::class)
                ->findOneBy(['name' => $item->getProduct()])
                ?? $entityManager->getRepository(Accessory::class)
                    ->findOneBy(['name' => $item->getProduct()]);

            $productImage = $YOUR_DOMAIN . '/img/default.png';
            if ($product_object) {
                $illustration = method_exists($product_object, 'getIllustrations')
                    ? $product_object->getIllustrations()->first()
                    : null;
                if ($illustration) {
                    $productImage = $YOUR_DOMAIN . '/uploads/' . $product_object->getUploadDirectory() . '/' . $illustration->getImage();
                }
            }

            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => round($item->getPriceTTC() * 100),
                    'product_data' => [
                        'name' => $item->getProduct(),
                        'images' => [$productImage],
                    ],
                ],
                'quantity' => $item->getQuantity(),
            ];
        }

        // 2️⃣ Livraison
        if ($order->getCarrierPrice() > 0) {
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => round($order->getCarrierPrice() * 100),
                    'product_data' => [
                        'name' => 'Livraison',
                        'images' => [$YOUR_DOMAIN . '/img/delivery.jpg'],
                    ],
                ],
                'quantity' => 1,
            ];
        }

        // 3️⃣ Remise totale (ligne négative)
        $remise = $panier->getReduction($promotionService, $promo);
        if ($remise > 0) {
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => round(-$remise * 100),
                    'product_data' => [
                        'name' => 'Remise promotionnelle',
                    ],
                ],
                'quantity' => 1,
            ];
        }

        // 🔑 Stripe API key
        Stripe::setApiKey('sk_test_51KNdRaBMBArCOnoiBGyovclE3rWKPO9X8dngKjHXezHj9SXaWeC3HrqOz7LCZAtXpVrJQzbx3PBPucDocAP8anBu00ZjyOIrSx');

        // 🧾 Création de la session Checkout
        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => $product_for_stripe,
            'mode' => 'payment',
            'discounts' => $remise > 0 ? [[
                'coupon' => $couponIdStripe, // ID du coupon Stripe
            ]] : [],
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);


        // 🔹 Sauvegarde de la session Stripe dans la commande
        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        // 🔹 Redirection vers Stripe
        return $this->redirect($checkout_session->url);
    }
}
