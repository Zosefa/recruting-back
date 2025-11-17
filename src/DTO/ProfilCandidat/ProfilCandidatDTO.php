<?php

namespace App\DTO\ProfilCandidat;

use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Utilisateur;

class ProfilCandidatDTO
{
    #[Assert\NotBlank()]
    public Utilisateur $utilisateur;

    #[Assert\NotBlank()]
    public string $nom;

    #[Assert\NotBlank()]
    public string $prenom;

    public ?string $localisation = null;

    public ?string $resume = null;

    public ?int $anneExperience = null;

    public bool $profilCompleted = false;


    public function __construct(
        Utilisateur $utilisateur,
        string $nom,
        string $prenom,
        ?string $localisation = null,
        ?string $resume = null,
        ?int $anneExperience = null,
        bool $profilCompleted
    )
    {
        $this->utilisateur = $utilisateur;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->localisation = $localisation;
        $this->resume = $resume;
        $this->anneExperience = $anneExperience;
        $this->profilCompleted = $profilCompleted;
    }
}
