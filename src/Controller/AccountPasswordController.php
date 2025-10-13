<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/modifier-mon-mot-de-passe', name: 'account_password')]
    public function index(Request $request, UserPasswordHasherInterface $hasher, Cart $cart): Response
    {
        $cartContent = $cart->getFull();
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $notification = null;
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('old_password')->getData();
            if ($hasher->isPasswordValid($user, $oldPassword)) {
                $newPassword = $form->get('new_password')->getData();
                $user->setPassword($hasher->hashPassword($user, $newPassword));
                $this->entityManager->flush();
                $notification = 'Votre mot de passe a bien été mis à jour.';
            } else {
                $notification = "Votre mot de passe actuel n'est pas correct.";
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
            'panier' => $cartContent
        ]);
    }
}
