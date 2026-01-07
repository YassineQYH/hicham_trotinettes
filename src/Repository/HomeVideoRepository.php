<?php

namespace App\Repository;

use App\Entity\HomeVideo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HomeVideo>
 */
class HomeVideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HomeVideo::class);
    }

    /**
     * 🎯 Récupère la vidéo active à afficher sur la home
     * (la première par position)
     */
    public function findActiveVideo(): ?HomeVideo
    {
        return $this->createQueryBuilder('hv')
            ->andWhere('hv.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('hv.position', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * 🔎 Vérifie s'il existe déjà une autre vidéo active
     * (utile pour éviter plusieurs vidéos actives en même temps)
     */
    public function findOtherActiveVideos(?int $excludeId = null): array
    {
        $qb = $this->createQueryBuilder('hv')
            ->andWhere('hv.isActive = :active')
            ->setParameter('active', true);

        if ($excludeId !== null) {
            $qb->andWhere('hv.id != :id')
               ->setParameter('id', $excludeId);
        }

        return $qb
            ->getQuery()
            ->getResult();
    }
}
