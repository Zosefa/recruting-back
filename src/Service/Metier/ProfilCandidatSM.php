<?php

namespace App\Service\Metier;

use App\DTO\ProfilCandidat\ProfilCandidatDTO;
use App\Entity\ProfilCandidat;
use App\Entity\Utilisateur;
use App\Repository\ProfilCandidatRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProfilCandidatSM
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProfilCandidatRepository $profilCandidatRepository
    )
    {}

    public function create(ProfilCandidatDTO $dto)
    {
        $profilCondidat = new ProfilCandidat();
        $profilCondidat
            ->setUtilisateur($dto->utilisateur)
            ->setNom($dto->nom)
            ->setPrenom($dto->prenom)
            ->setLocalisation($dto->localisation)
            ->setAnneExperience($dto->anneExperience)
            ->setResume($dto->resume)
            ->setProfilCompleted($dto->profilCompleted);

        $this->em->persist($profilCondidat);
        $this->em->flush();

        return $profilCondidat;
    }

    public function update(ProfilCandidatDTO $dto, ProfilCandidat $profilCondidat)
    {
        $profilCondidat
            ->setUtilisateur($dto->utilisateur)
            ->setNom($dto->nom)
            ->setPrenom($dto->prenom)
            ->setLocalisation($dto->localisation)
            ->setAnneExperience($dto->anneExperience)
            ->setResume($dto->resume)
            ->setProfilCompleted($dto->profilCompleted);

        $this->em->persist($profilCondidat);
        $this->em->flush();

        return $profilCondidat;
    }

    public function findAll()
    {
        return $this->profilCandidatRepository->findAll();
    }

    public function findById(int $id)
    {
        return $this->profilCandidatRepository->find($id);
    }

    public function findByUtilisateur(Utilisateur $utilisateur)
    {
        return $this->profilCandidatRepository->findOneBy(['utilisateur' => $utilisateur]);
    }

    public function findByNomOrPrenom(string $critere)
    {
        return $this->profilCandidatRepository->findByNomOrPrenom($critere);
    }
}
