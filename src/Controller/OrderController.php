<?php

namespace App\Controller;

use DateTime;
use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Repository\WeightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'order')]
    public function index(Cart $cart, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (!$user->getAddresses()->getValues()) {
            return $this->redirectToRoute('account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $user
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'panier' => $cart->getFull()
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'order_recap', methods: ['POST'])]
    public function add(Cart $cart, Request $request, WeightRepository $weightRepo): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $user
        ]);

        $panier = $cart->getFull();
        $poids = 0.0;
        $quantity_product = 0;

        foreach ($panier as $element) {
            $poids += $element['product']->getWeight()->getKg() * $element['quantity'];
            $quantity_product += $element['quantity'];
        }

        $prix = $weightRepo->findByKgPrice($poids)->getPrice();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $delivery = $form->get('addresses')->getData();
            $deliveryContent = $delivery->getFirstname().' '.$delivery->getLastname()
                .'</br>'.$delivery->getPhone()
                .($delivery->getCompany() ? '</br'.$delivery->getCompany() : '')
                .'</br>'.$delivery->getAddress()
                .'</br>'.$delivery->getPostal().' '.$delivery->getCity()
                .'</br>'.$delivery->getCountry();

            $order = new Order();
            $reference = (new DateTime())->format('dmY').'-'.uniqid();
            $order->setReference($reference)
                  ->setUser($user)
                  ->setCreatedAt(new DateTime())
                  ->setCarrierPrice($prix)
                  ->setDelivery($deliveryContent)
                  ->setState(0);

            $this->entityManager->persist($order);

            foreach ($panier as $element) {
                $details = new OrderDetails();
                $details->setMyOrder($order)
                        ->setProduct($element['product']->getName())
                        ->setWeight($element['product']->getWeight())
                        ->setQuantity($element['quantity'])
                        ->setPrice($element['product']->getPrice())
                        ->setTotal($element['product']->getPrice() * $element['quantity']);
                $this->entityManager->persist($details);
            }

            $this->entityManager->flush();

            return $this->render('order/add.html.twig', [
                'cart' => $panier,
                'delivery' => $deliveryContent,
                'reference' => $order->getReference(),
                'price' => $prix,
                'totalLivraison' => null
            ]);
        }

        return $this->redirectToRoute('cart');
    }
}
