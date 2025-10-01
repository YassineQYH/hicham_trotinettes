<?php

namespace App\Controller;

use App\Entity\Accessory;
use App\Entity\Trottinette;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrottinetteController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-trottinettes', name: 'nos_trottinettes')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        // On utilise le repository pour créer une query
        $query = $this->entityManager
                    ->getRepository(Trottinette::class)
                    ->createQueryBuilder('t')
                    ->getQuery();

        // Paginate la query
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );

        // On passe la pagination à la vue
        return $this->render('trottinette/index.html.twig', [
            'trottinettes' => $pagination
        ]);
    }



    #[Route('/trottinette/{slug}', name: 'trottinette_show')]
    public function show(string $slug): Response
    {
        $trottinette = $this->entityManager->getRepository(Trottinette::class)
            ->findOneBy(['slug' => $slug]);

        if (!$trottinette) {
            throw $this->createNotFoundException('Cette trottinette n’existe pas.');
        }

        $accessoires = $trottinette->getAccessories(); // relation ManyToMany

        return $this->render('trottinette/single_trott.html.twig', [
            'trottinette' => $trottinette,
            'accessoires' => $accessoires
        ]);
    }

    #[Route('/trottinette/{slug}/accessoires', name: 'trottinette_accessoires')]
    public function showAccessoires(string $slug): Response
    {
        $trottinette = $this->entityManager->getRepository(Trottinette::class)
            ->findOneBy(['slug' => $slug]);

        if (!$trottinette) {
            throw $this->createNotFoundException('Cette trottinette n’existe pas.');
        }

        // Récupère tous les objets Accessory liés à cette trottinette
        $accessoires = [];
        foreach ($trottinette->getTrottinetteAccessories() as $ta) {
            $accessoires[] = $ta->getAccessory();
        }

        return $this->render('trottinette/show-all-access.html.twig', [
            'trottinette' => $trottinette,
            'accessoires' => $accessoires
        ]);
    }

}
