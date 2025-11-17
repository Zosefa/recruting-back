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
        $checkEmailExisting = $this->findByEmail($dto->email);
        if(is_object($checkEmailExisting))
        {
            return ['success' => false , 'msg' => 'Cette email est dejas enregistré !'];
        }
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
        return $this->utilisateurSM->findByEmail($email);
    }
}
