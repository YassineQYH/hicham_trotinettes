<?php

namespace App\Controller;

use App\Entity\Accessory;
use App\Entity\Illustrationaccess;
use App\Repository\AccessoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccessoryController extends AbstractController
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, AccessoryRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    #[Route('/nos-accessoires', name: 'app_accessoires')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $articles = $this->repository->findAll();
        $accessories = $paginator->paginate($articles, $request->query->getInt('page', 1), 9);

        return $this->render('accessoires/index.html.twig', [
            'accessories' => $accessories
        ]);
    }

    #[Route('/accessoire/{slug}', name: 'app_accessoire_show')]
    public function show(string $slug): Response
    {
        $accessory = $this->repository->findOneBySlug($slug);
        if (!$accessory) {
            return $this->redirectToRoute('app_accessoires');
        }

        $illustrations = $this->entityManager->getRepository(Illustrationaccess::class)->findBy(['accessory' => $accessory]);

        return $this->render('accessoires/show.html.twig', [
            'accessory' => $accessory,
            'illustrations' => $illustrations
        ]);
    }
}
