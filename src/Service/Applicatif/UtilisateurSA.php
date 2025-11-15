<?php

namespace App\Service\Applicatif;

use App\DTO\Utilisateur\UtilisateurDTO;
use App\Service\Metier\UtilisateurSM;

class UtilisateurSA
{
    public function __construct(
        private UtilisateurSM $utilisateurSM
    )
    {}

    public function create(UtilisateurDTO $dto)
    {
        return $this->utilisateurSM->create($dto);
    }

    public function update()
    {

    }

    public function findAll()
    {
        return $this->utilisateurSM->findAll();
    }

    public function findById(int $id)
    {
        return $this->utilisateurSM->findById($id);
    }

    public function findByEmail(string $email)
    {
        return $this->findByEmail($email);
    }
}
