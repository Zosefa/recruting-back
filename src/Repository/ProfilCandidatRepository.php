<?php

namespace App\Repository;

use App\Entity\ProfilCandidat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProfilCandidat>
 */
class ProfilCandidatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilCandidat::class);
    }

    public function findByNomOrPrenom(string $critere)
    {
        return $this->createQueryBuilder('pc')
            ->where('pc.nom LIKE :critere')
            ->orWhere('pc.prenom LIKE :critere')
            ->setParameter('critere', '%' . $critere . '%')
            ->getQuery()
            ->getResult();
    }
}
