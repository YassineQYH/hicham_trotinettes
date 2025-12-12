<?php

namespace App\Repository;

use App\Entity\Promotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Promotion>
 *
 * @method Promotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promotion[]    findAll()
 * @method Promotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }

    /**
     * Trouve une promotion active par son code
     */
    public function findActiveByCode(string $code): ?Promotion
    {
        return $this->createQueryBuilder('p')
            ->where('p.code = :code')
            ->andWhere('p.startDate <= :now')
            ->andWhere('(p.endDate IS NULL OR p.endDate >= :now)')
            ->setParameter('code', $code)
            ->setParameter('now', new \DateTimeImmutable())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retourne toutes les promotions actives
     */
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.startDate <= :now')
            ->andWhere('(p.endDate IS NULL OR p.endDate >= :now)')
            ->setParameter('now', new \DateTimeImmutable())
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne toutes les promotions disponibles (actives, non expirées, et non utilisées)
     */
    public function findAllAvailable(): array
    {
        $now = new \DateTimeImmutable();

        return $this->createQueryBuilder('p')
            ->andWhere('p.startDate <= :now')
            ->andWhere('(p.endDate IS NULL OR p.endDate >= :now)')
            ->andWhere('p.used < p.quantity')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }
}
