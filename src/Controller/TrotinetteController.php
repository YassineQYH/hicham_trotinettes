<?php

namespace App\Controller;

use App\Entity\Trotinette;
use App\Entity\Illustration;
use App\Repository\TrotinetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrotinetteController extends AbstractController
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, TrotinetteRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    #[Route('/nos-trotinettes', name: 'app_trotinettes')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $articles = $this->repository->findAll();
        $trotinettes = $paginator->paginate($articles, $request->query->getInt('page', 1), 9);

        return $this->render('trotinettes/index.html.twig', [
            'trotinettes' => $trotinettes,
        ]);
    }

    #[Route('/trotinette/{slug}', name: 'app_trotinette_show')]
    public function show(string $slug): Response
    {
        $trotinette = $this->repository->findOneBy(['slug' => $slug]);

        if (!$trotinette) {
            return $this->redirectToRoute('app_trotinettes');
        }

        $illustrations = $this->entityManager->getRepository(Illustration::class)->findBy(['trotinette' => $trotinette]);

        return $this->render('trotinettes/show.html.twig', [
            'trotinette' => $trotinette,
            'illustrations' => $illustrations,
        ]);
    }

    #[Route('/nos-trotinettes/{slug}/accessoires', name: 'app_trotinette_accessories')]
    public function accessoires(Trotinette $trotinette, PaginatorInterface $paginator, Request $request): Response
    {
        $accessories = $trotinette->getAccessories(); // suppose que tu as ajouté la relation dans l'entité Trotinette

        $paginatedAccessories = $paginator->paginate($accessories, $request->query->getInt('page', 1), 6);

        return $this->render('trotinettes/accessories.html.twig', [
            'trotinette' => $trotinette,
            'accessories' => $paginatedAccessories,
        ]);
    }
}
