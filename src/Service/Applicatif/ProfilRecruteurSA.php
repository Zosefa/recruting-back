<?php

namespace App\Service\Applicatif;

use App\DTO\ProfilRecruteur\ProfilRecruteurDTO;
use App\Entity\ProfilRecruteur;
use App\Service\Metier\ProfilRecruteurSM;
use App\Service\Metier\UtilisateurSM;

class ProfilRecruteurSA
{
    public function __construct(
        private ProfilRecruteurSM $profilRecruteurSM,
        private UtilisateurSM $utilisateurSM
    )
    {}

    public function create(ProfilRecruteurDTO $dto)
    {
        $compteExisting = $this->profilRecruteurSM->findByUtilisateur($dto->utilisateurId);
        if(is_object($compteExisting))
        {
            return ['success' => false, 'msg' => 'Cette utilisateur a dejas un profile enregistré!'];
        }
        return $this->profilRecruteurSM->create($dto);
    }

    public function update(ProfilRecruteurDTO $dto, ProfilRecruteur $profilRecruteur)
    {
        return $this->profilRecruteurSM->update($dto, $profilRecruteur);
    }

    public function findAll()
    {
        return $this->profilRecruteurSM->findAll();
    }

    public function findById(int $id)
    {
        return $this->profilRecruteurSM->findById($id);
    }

    public function findByNomEntreprise(string $nom)
    {
        return $this->profilRecruteurSM->findByNomEntreprise($nom);
    }

    public function findByUtilisateur(int $utilisateurId)
    {
        $utilisateur = $this->utilisateurSM->findById($utilisateurId);

        return $this->profilRecruteurSM->findByUtilisateur($utilisateur);
    }
}
