<?php

namespace App\Repository;

use App\Entity\ProfilRecruteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProfilRecruteur>
 */
class ProfilRecruteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilRecruteur::class);
    }

    public function findByNomEntreprise(string $nom)
    {
        return $this->createQueryBuilder('pr')
            ->where('pr.nomEntreprise LIKE :nom')
            ->setParameter('nom', '%' . $nom . '%')
            ->getQuery()
            ->getResult();
    }
}
