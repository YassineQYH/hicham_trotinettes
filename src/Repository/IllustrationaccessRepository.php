<?php

namespace App\Repository;

use App\Entity\Illustrationaccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Illustrationaccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method Illustrationaccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method Illustrationaccess[]    findAll()
 * @method Illustrationaccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IllustrationaccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Illustrationaccess::class);
    }
}
