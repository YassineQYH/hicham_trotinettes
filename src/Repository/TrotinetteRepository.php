<?php

namespace App\Repository;

use App\Entity\Trotinette;
use App\Entity\ModelTrotinette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trotinette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trotinette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trotinette[]    findAll()
 * @method Trotinette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrotinetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trotinette::class);
    }

    /**
     * @return Trotinette[]
     */
    public function findAllOrderByModel(ModelTrotinette $modelTrotinette)
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'm')
            ->leftJoin('t.modelTrotinette', 'm')
            ->andWhere('m = :model')
            ->setParameter('model', $modelTrotinette)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
