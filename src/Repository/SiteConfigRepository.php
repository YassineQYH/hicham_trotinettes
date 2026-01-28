<?php

namespace App\Repository;

use App\Entity\SiteConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NoResultException;

class SiteConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SiteConfig::class);
    }

    public function getValue(string $name): ?string
    {
        try {
            return $this->createQueryBuilder('c')
                ->select('c.value')
                ->where('c.name = :name')
                ->setParameter('name', $name)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

}
