<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'account')]
    public function index(Cart $cart): Response
    {
        $cartItems = $cart->getFull();

        return $this->render('account/index.html.twig', [
            'cart' => $cartItems,
        ]);
    }
}
