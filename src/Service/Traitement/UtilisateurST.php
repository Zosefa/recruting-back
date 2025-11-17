<?php

namespace App\Service\Traitement;

use App\Service\Applicatif\ProfilCandidatSA;
use App\Service\Applicatif\UtilisateurSA;

class UtilisateurST
{
    public function __construct(
        private UtilisateurSA $utilisateurSA,
        private ProfilCandidatSA $profilCandidatSA
    )
    {}

    public function findInfoUser(int $utilisateurId)
    {
        $utilisateur = $this->utilisateurSA->findById($utilisateurId);

        if (!$utilisateur) {
            return null; 
        }

        // On récupère les infos du candidat si elles existent
        $infoCandidat = $this->profilCandidatSA->findByUtilisateur($utilisateur);

        $info = $infoCandidat ?? [];

        // Détermination du type d'utilisateur
        $roles = $utilisateur->getRoles();
        if (in_array('ROLE_ADMIN', $roles)) {
            $type = 'admin';
        } elseif (in_array('ROLE_RECRUITER', $roles)) {
            $type = 'recruteur';
        } else {
            $type = 'condidat';
        }

        // Ajout du type à l'info
        if (is_array($info)) {
            $info['type'] = $type;
        } else {
            // si $info est un objet, on peut ajouter un setter ou créer un tableau
            $info = [
                'profil' => $info,
                'type' => $type
            ];
        }

        return $info;
    }

}
