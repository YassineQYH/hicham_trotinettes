<?php

namespace App\Controller;

use App\Entity\Accessory;
use App\Entity\Illustrationaccess;
use App\Entity\ModelTrotinette;
use App\Repository\AccessoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ModelTrotinetteRepository;
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
    public function index(Request $request, ModelTrotinetteRepository $modelTrotinette, PaginatorInterface $paginator): Response
    {
        $articles = $this->entityManager->getRepository(Accessory::class)->findAll();
        $accessories = $paginator->paginate($articles, $request->query->getInt('page', 1), 9);

        $models = $modelTrotinette->findAll();

        return $this->render('accessoires/index.html.twig', [
            'accessories' => $accessories,
            'models' => $models
        ]);
    }

    #[Route('/accessoire/{slug}', name: 'app_accessoire_show')]
    public function show(string $slug, ModelTrotinetteRepository $modelTrotinette): Response
    {
        $accessory = $this->entityManager->getRepository(Accessory::class)->findOneBySlug($slug);
        if (!$accessory) {
            return $this->redirectToRoute('app_accessoires');
        }

        $models = $modelTrotinette->findAll();
        $illustrations = $this->entityManager->getRepository(Illustrationaccess::class)->findByAccessory($accessory);

        return $this->render('accessoires/show.html.twig', [
            'accessory' => $accessory,
            'illustrations' => $illustrations,
            'models' => $models
        ]);
    }

    #[Route('/nos-accessoires/model-{model}', name: 'app_accessoire_by_model')]
    public function choixModel(ModelTrotinette $model, ModelTrotinetteRepository $modelTrotinette, PaginatorInterface $paginator, Request $request): Response
    {
        $articles = $this->repository->findBy(['modelTrotinette' => $model]);
        $accessories = $paginator->paginate($articles, $request->query->getInt('page', 1), 6);

        $models = $modelTrotinette->findAll();

        return $this->render('accessoires/model.html.twig', [
            'accessories' => $accessories,
            'models' => $models
        ]);
    }
}
