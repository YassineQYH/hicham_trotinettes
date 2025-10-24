<?php

namespace App\Service;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use App\Classe\Mail;

class DeliveryChecker
{
    private OrderRepository $orderRepository;
    private EntityManagerInterface $em;
    private MailerInterface $mailer;

    public function __construct(OrderRepository $orderRepository, EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->orderRepository = $orderRepository;
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function checkDeliveries(): void
    {
        // Récupère toutes les commandes en cours de livraison (deliveryState = 2)
        $orders = $this->orderRepository->findBy(['deliveryState' => 2]);

        foreach ($orders as $order) {
            $primaryTracking = $order->getTrackingNumber();
            $primaryCarrier = $order->getCarrier();

            $secondaryTracking = $order->getSecondaryCarrierTrackingNumber();
            $secondaryCarrier = $order->getSecondaryCarrier(); // Optionnel pour l’instant, null si non utilisé

            // Skip si pas de tracking principal ou carrier
            if (!$primaryTracking || !$primaryCarrier) {
                continue;
            }

            $delivered = $this->isDelivered($primaryTracking, $primaryCarrier);

            // Vérifie le second tracking si existant et non livré
            if (!$delivered && $secondaryTracking && $secondaryCarrier) {
                $delivered = $this->isDelivered($secondaryTracking, $secondaryCarrier);
            }

            if ($delivered) {
                $order->setDeliveryState(3);
                $this->em->persist($order);

                // Envoi d'un mail via Mailjet
                $mail = new Mail();
                $content = "Bonjour " . $order->getUser()->getFirstName() . ",<br>"
                    . "Votre commande #" . $order->getReference() . " a été livrée avec succès.<br>"
                    . "Merci pour votre confiance !";
                $mail->send(
                    $order->getUser()->getEmail(),
                    $order->getUser()->getFirstName(),
                    "Votre commande est livrée !",
                    $content
                );
            }
        }

        $this->em->flush();
    }

    private function isDelivered(string $trackingNumber, string $carrier): bool
    {
        // 🔧 MODE DEV : on simule que le colis est livré
        return true; // Décommenter pour test

        // -- Partie réelle avec l'API Track123 --
        /* $apiKey = '76b446ff2aa94c6f9622c0b4acd4dab3';
        $url = "https://api.track123.com/v1/trackings/{$trackingNumber}?carrier={$carrier}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$apiKey}"]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        // Retourne true si le colis est livré
        return isset($data['status']) && $data['status'] === 'delivered'; */
    }
}
