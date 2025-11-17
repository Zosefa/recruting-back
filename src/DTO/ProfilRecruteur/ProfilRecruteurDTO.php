<?php

namespace App\DTO\ProfilRecruteur;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Utilisateur;

class ProfilRecruteurDTO
{
    #[Assert\NotBlank()]
    public Utilisateur $utilisateurId;

    #[Assert\NotBlank()]
    public string $nomEntreprise;

    public ?string $logoEntreprise;

    public ?string $poste = null;

    #[Assert\NotBlank()]
    public string $adresse;

    #[Assert\NotBlank()]
    public bool $verifie = false;

    public function __construct(
        Utilisateur $utilisateurId,
        string $nomEntreprise,
        ?string $logoEntreprise,
        ?string $poste,
        string $adresse,
        bool $verifie
    )
    {
        $this->utilisateurId = $utilisateurId;
        $this->nomEntreprise = $nomEntreprise;
        $this->logoEntreprise = $logoEntreprise;
        $this->poste = $poste;
        $this->adresse = $adresse;
        $this->verifie = $verifie;
    }
}
