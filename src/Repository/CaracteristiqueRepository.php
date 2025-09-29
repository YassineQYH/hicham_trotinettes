<?php

namespace App\Repository;

use App\Entity\Caracteristique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Caracteristique>
 *
 * @method Caracteristique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Caracteristique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Caracteristique[]    findAll()
 * @method Caracteristique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaracteristiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Caracteristique::class);
    }

    // Tu peux ajouter tes méthodes personnalisées ici
}
