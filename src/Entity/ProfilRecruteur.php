<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'profils_recruteur')]
class ProfilRecruteur
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id")]
    #[Groups(['profil_recruteur:read'])]
    private Utilisateur $utilisateur;

    #[ORM\Column(name: 'nom_entreprise', type: Types::STRING, length: 255)]
    #[Groups(['profil_recruteur:read'])]
    private string $nomEntreprise;

    #[ORM\Column(name: 'logo_entreprise', type: Types::STRING, length: 255)]
    #[Groups(['profil_recruteur:read'])]
    private ?string $logoEntreprise = null;

    #[ORM\Column(name: 'poste', type: Types::STRING, length: 255)]
    #[Groups(['profil_recruteur:read'])]
    private ?string $poste = null;

    #[ORM\Column(name: 'adresse', type: Types::STRING, length: 255)]
    #[Groups(['profil_recruteur:read'])]
    private string $adresse;

    #[ORM\Column(name: 'verifie', type: Types::BOOLEAN)]
    #[Groups(['profil_recruteur:read'])]
    private bool $verifie = false;

    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getNomEntreprise(): string
    {
        return $this->nomEntreprise;
    }

    public function setNomEntreprise(string $nomEntreprise): self
    {
        $this->nomEntreprise = $nomEntreprise;
        return $this;
    }

    public function getLogoEntreprise(): ?string
    {
        return $this->logoEntreprise;
    }

    public function setLogoEntreprise(?string $logoEntreprise): self
    {
        $this->logoEntreprise = $logoEntreprise;
        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(?string $poste): self
    {
        $this->poste = $poste;
        return $this;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getVerifie(): bool
    {
        return $this->verifie;
    }

    public function setVerifie(bool $verifie): self
    {
        $this->verifie = $verifie;
        return $this;
    }
}
