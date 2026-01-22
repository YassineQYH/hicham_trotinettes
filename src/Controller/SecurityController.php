<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserRegistrationToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;


class SecurityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * 🔒 Déconnexion utilisateur
     */
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Intercepté par le firewall.');
    }

    /**
     * ✅ Validation du compte
     */
    #[Route('/verify-account/{token}', name: 'verify_account')]
    public function verify(string $token): Response
    {
        $registration = $this->entityManager
            ->getRepository(UserRegistrationToken::class)
            ->findOneBy(['token' => $token]);

        if (
            !$registration ||
            $registration->getExpiresAt() < new \DateTimeImmutable()
        ) {
            $this->addFlash('error', 'Lien invalide ou expiré.');
            return $this->redirectToRoute('app_home');
        }

        // 👤 Création du vrai User
        $user = new User();
        $user->setEmail($registration->getEmail());
        $user->setPassword($registration->getPasswordHash());
        $user->setFirstName($registration->getFirstName());
        $user->setLastName($registration->getLastName());
        $user->setTel($registration->getTel());
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->remove($registration);
        $this->entityManager->flush();

        $this->addFlash(
            'info-alert',
            'Compte activé avec succès 🎉 Vous pouvez maintenant vous connecter.'
        );

        return $this->redirectToRoute('app_home');
    }

    #[Route('/set-password/{token}', name: 'app_set_password')]
    public function setPassword(
        Request $request,
        string $token,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $user = $userRepository->findOneBy(['passwordResetToken' => $token]);

        if (!$user || $user->getPasswordResetTokenExpiresAt() < new \DateTime()) {
            $this->addFlash('error', 'Lien invalide ou expiré.');
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createFormBuilder()
            ->add('password', PasswordType::class, ['label' => 'Mot de passe'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword($user, $form->get('password')->getData())
            );
            $user->setPasswordResetToken(null);
            $user->setPasswordResetTokenExpiresAt(null);

            $this->entityManager->flush();

            $this->addFlash('success', 'Mot de passe créé avec succès !');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/set_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
