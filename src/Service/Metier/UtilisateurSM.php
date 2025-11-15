<?php

namespace App\Service\Metier;

use App\DTO\Utilisateur\UtilisateurDTO;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Service\Traitement\PasswordST;
use Doctrine\ORM\EntityManagerInterface;

class UtilisateurSM
{
    public function __construct(
        private UtilisateurRepository $utilisateurRepository,
        private EntityManagerInterface $em,
        private PasswordST $passwordST
    )
    {}

    public function create(UtilisateurDTO $dto)
    {
        $utilisateur = new Utilisateur();

        $utilisateur
            ->setEmail($dto->email)
            ->setRoles($dto->role)
            ->setIsActive($dto->isActive)
            ->setPassword($this->passwordST->hashPassword($utilisateur, $dto->password))
            ->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($utilisateur);
        $this->em->flush();

        return $utilisateur;
    }

    public function update()
    {

    }

    public function findAll()
    {
        return $this->utilisateurRepository->findAll();
    }

    public function findById(int $id)
    {
        return $this->utilisateurRepository->find($id);
    }

    public function findByEmail(string $email)
    {
        return $this->utilisateurRepository->findOneBy(['email' => $email]);
    }
}
