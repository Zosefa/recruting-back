<?php

namespace App\DTO\Utilisateur;

use Symfony\Component\Validator\Constraints as Assert;

class UtilisateurDTO
{
    #[Assert\NotBlank()]
    #[Assert\Length(max: 100)]
    public string $email;

    #[Assert\NotBlank()]
    public array $role = [];

    #[Assert\NotBlank()]
    public string $password;

    #[Assert\NotBlank()]
    public bool $isActive;

    public function __construct(
        string $email,
        array $role,
        string $password,
        bool $isActive
    )
    {
        $this->email = $email;
        $this->role = $role;
        $this->password = $password;
        $this->isActive = $isActive;
    }
}
