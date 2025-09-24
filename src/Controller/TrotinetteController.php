<?php

namespace App\Controller;

use App\Entity\Trotinette;
use App\Entity\Illustration;
use App\Entity\ModelTrotinette;
use App\Repository\TrotinetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ModelTrotinetteRepository;
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
    public function index(Request $request, ModelTrotinetteRepository $modelTrotinette, PaginatorInterface $paginator): Response
    {
        $articles = $this->entityManager->getRepository(Trotinette::class)->findAll();
        $trotinettes = $paginator->paginate($articles, $request->query->getInt('page', 1), 9);

        $models = $modelTrotinette->findAll(); // ⚡ harmonisé

        return $this->render('trotinettes/index.html.twig', [
            'trotinettes' => $trotinettes,
            'models' => $models
        ]);
    }

    #[Route('/trotinette/{slug}', name: 'app_trotinette_show')]
    public function show(string $slug, ModelTrotinetteRepository $modelTrotinette): Response
    {
        $trotinette = $this->entityManager->getRepository(Trotinette::class)->findOneBySlug($slug);
        if (!$trotinette) {
            return $this->redirectToRoute('app_trotinettes');
        }

        $models = $modelTrotinette->findAll(); // ⚡ harmonisé
        $illustrations = $this->entityManager->getRepository(Illustration::class)->findBy(['trotinette' => $trotinette]);

        return $this->render('trotinettes/show.html.twig', [
            'trotinette' => $trotinette,
            'illustrations' => $illustrations,
            'models' => $models
        ]);
    }

    #[Route('/nos-trotinettes/model-{model}', name: 'app_trotinette_by_model')]
    public function choixModel(ModelTrotinette $model, ModelTrotinetteRepository $modelTrotinette, PaginatorInterface $paginator, Request $request): Response
    {
        $articles = $this->repository->findAllOrderByModel($model);
        $trotinettes = $paginator->paginate($articles, $request->query->getInt('page', 1), 6);

        $models = $modelTrotinette->findAll(); // ⚡ harmonisé

        return $this->render('trotinettes/model.html.twig', [
            'trotinettes' => $trotinettes,
            'models' => $models
        ]);
    }
}
