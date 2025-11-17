<?php

namespace App\Service\Metier;

use App\DTO\ProfilRecruteur\ProfilRecruteurDTO;
use App\Entity\ProfilRecruteur;
use App\Entity\Utilisateur;
use App\Repository\ProfilRecruteurRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProfilRecruteurSM
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProfilRecruteurRepository $profilRecruteurRepostory
    )
    {}

    public function create(ProfilRecruteurDTO $dto)
    {
        $profilRecruteur = new ProfilRecruteur();
        $profilRecruteur
            ->setUtilisateur($dto->utilisateurId)
            ->setNomEntreprise($dto->nomEntreprise)
            ->setLogoEntreprise($dto->logoEntreprise)
            ->setAdresse($dto->adresse)
            ->setPoste($dto->poste)
            ->setVerifie($dto->verifie);

        $this->em->persist($profilRecruteur);
        $this->em->flush();

        return $profilRecruteur;
    }

    public function update(ProfilRecruteurDTO $dto, ProfilRecruteur $profilRecruteur)
    {
        $profilRecruteur
            ->setUtilisateur($dto->utilisateurId)
            ->setNomEntreprise($dto->nomEntreprise)
            ->setLogoEntreprise($dto->logoEntreprise)
            ->setAdresse($dto->adresse)
            ->setPoste($dto->poste)
            ->setVerifie($dto->verifie);

        $this->em->persist($profilRecruteur);
        $this->em->flush();

        return $profilRecruteur;
    }

    public function findAll()
    {
        return $this->profilRecruteurRepostory->findAll();
    }

    public function findById(int $id)
    {
        return $this->profilRecruteurRepostory->find($id);
    }

    public function findByNomEntreprise(string $nom)
    {
        return $this->profilRecruteurRepostory->findByNomEntreprise($nom);
    }

    public function findByUtilisateur(Utilisateur $utilisateur)
    {
        return $this->profilRecruteurRepostory->findOneBy(['utilisateur' => $utilisateur]);
    }
}
