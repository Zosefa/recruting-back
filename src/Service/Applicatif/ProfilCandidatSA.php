<?php

namespace App\Service\Applicatif;

use App\DTO\ProfilCandidat\ProfilCandidatDTO;
use App\Entity\ProfilCandidat;
use App\Entity\Utilisateur;
use App\Service\Metier\ProfilCandidatSM;

class ProfilCandidatSA
{
    public function __construct(
        private ProfilCandidatSM $profilCandidatSM
    )
    {}

    public function create(ProfilCandidatDTO $dto)
    {
        $compteExisting = $this->profilCandidatSM->findByUtilisateur($dto->utilisateur);
        if(is_object($compteExisting))
        {
            return ['success' => false, 'msg' => 'Cette utilisateur a dejas un profile enregistré!'];
        }
        return $this->profilCandidatSM->create($dto);
    }

    public function update(ProfilCandidatDTO $dto, ProfilCandidat $profilCandidat)
    {
        return $this->profilCandidatSM->update($dto, $profilCandidat);
    }

    public function findAll()
    {
        return $this->profilCandidatSM->findAll();
    }

    public function findById(int $id)
    {
        return $this->profilCandidatSM->findById($id);
    }

    public function findByNomOrPrenom(string $critere)
    {
        return $this->profilCandidatSM->findByNomOrPrenom($critere);
    }

    public function findByUtilisateur(Utilisateur $utilisateur)
    {
        return $this->profilCandidatSM->findByUtilisateur($utilisateur);
    }
}
