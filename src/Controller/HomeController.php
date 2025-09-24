<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Header;
use App\Entity\Trotinette;
use App\Entity\Accessory;
use App\Entity\ModelTrotinette;
use App\Form\ContactType;
use App\Repository\ModelTrotinetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $encoder,
        AuthenticationUtils $authenticationUtils,
        ModelTrotinetteRepository $modelTrotinetteRepository
    ): Response
    {
        // Formulaire de contact
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('notice', "Merci de m'avoir contacté. Je vous répondrais dans les meilleurs délais.");

            $data = $form->getData();
            $content = "Bonjour </br>
                        Vous avez reçu un message depuis Pergolazur. </br>
                        De l'utilisateur : <strong>".$data['name']."</strong></br>
                        De la société : <strong>".$data['company']."</strong></br>
                        N° de tel : <strong>".$data['tel']."</strong></br>
                        Adresse email : <strong style='color:black;'>".$data['email']."</strong> </br>
                        Message : ".$data['message']."</br></br>";

            $mail = new Mail();
            $mail->send(
                'yassine.qyh@gmail.com',
                'Pergolazur',
                'Vous avez reçu une nouvelle demande de contact',
                $content
            );
        }

        // Récupération des données
        $headers = $this->entityManager->getRepository(Header::class)->findAll();
        $models = $modelTrotinetteRepository->findAll(); // ⚡ harmonisé

        // ⚡ tu n’as plus de Product, je suppose que ça doit pointer sur Trotinette
        $products = $this->entityManager->getRepository(Trotinette::class)->findBy(['isBest' => 1]);
        $accessories = $this->entityManager->getRepository(Accessory::class)->findBy(['isBest' => 1]);

        return $this->render('home/index.html.twig', [
            'headers' => $headers,
            'models' => $models, // ⚡ toujours "models"
            'products' => $products,
            'accessories' => $accessories,
            'form' => $form->createView()
        ]);
    }
}
