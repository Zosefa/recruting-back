<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'profils_candidat')]
class ProfilCandidat
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id")]
    #[Groups(['profil_condidat:read'])]
    private Utilisateur $utilisateur;

    #[ORM\Column(name: 'nom', type: Types::STRING, length: 255)]
    #[Groups(['profil_condidat:read'])]
    private string $nom;

    #[ORM\Column(name: 'prenom', type: Types::STRING, length: 255)]
    #[Groups(['profil_condidat:read'])]
    private string $prenom;

    #[ORM\Column(name: 'localisation', type: Types::STRING, length: 255, nullable: true)]
    #[Groups(['profil_condidat:read'])]
    private ?string $localisation = null;

    #[ORM\Column(name: 'resume', type: Types::TEXT, nullable: true)]
    #[Groups(['profil_condidat:read'])]
    private ?string $resume = null;

    #[ORM\Column(name: 'annees_experience', type: Types::INTEGER, nullable: true)]
    #[Groups(['profil_condidat:read'])]
    private ?int $anneExperience = null;

    #[ORM\Column(name: 'profil_completed', type: Types::BOOLEAN)]
    #[Groups(['profil_condidat:read'])]
    private bool $profilCompleted = false;

    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): self
    {
        $this->localisation = $localisation;
        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(?string $resume): self
    {
        $this->resume = $resume;
        return $this;
    }

    public function getAnneExperience(): ?int
    {
        return $this->anneExperience;
    }

    public function setAnneExperience(?int $anneExperience): self
    {
        $this->anneExperience = $anneExperience;
        return $this;
    }

    public function getProfilCompleted(): bool
    {
        return $this->profilCompleted;
    }

    public function setProfilCompleted(bool $profilCompleted): self
    {
        $this->profilCompleted = $profilCompleted;

        return $this;
    }
}
