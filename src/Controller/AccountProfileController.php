<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Form\AccountProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountProfileController extends AbstractController
{
    #[Route('/compte/mes-informations', name: 'account_profile')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        Cart $cart
    ): Response {
        $cartContent = $cart->getFull();
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(AccountProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont bien été mises à jour.');
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView(),
            'cart' => $cartContent
        ]);
    }
}
