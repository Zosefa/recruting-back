-- Activer pgvector pour stocker les vecteurs
CREATE EXTENSION IF NOT EXISTS vector;

----------------------------------------
-- 1️⃣ UTILISATEURS & PROFILS
----------------------------------------

CREATE TABLE utilisateurs (
    id SERIAL PRIMARY KEY,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role TEXT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMPTZ DEFAULT now(),
    updated_at TIMESTAMPTZ DEFAULT now()
);

-- Profil candidat
CREATE TABLE profils_candidat (
    utilisateur_id INT PRIMARY KEY REFERENCES utilisateurs(id),
    nom_complet TEXT,
    localisation TEXT,
    resume TEXT,
    annees_experience INT DEFAULT 0,
    profil_completed BOOLEAN DEFAULT FALSE
);

CREATE TABLE telephones_candidat (
    id SERIAL PRIMARY KEY,
    candidat_id INT NOT NULL REFERENCES profils_candidat(utilisateur_id),
    telephone TEXT NOT NULL,
    is_phone_principal BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMPTZ DEFAULT now(),
    updated_at TIMESTAMPTZ DEFAULT now(),

    CONSTRAINT unique_phone_principal
        UNIQUE (candidat_id, is_phone_principal)
);

CREATE UNIQUE INDEX idx_unique_phone_principal
ON telephones_candidat (candidat_id)
WHERE is_phone_principal = true;

-- Profil recruteur
CREATE TABLE profils_recruteur (
    utilisateur_id INT PRIMARY KEY REFERENCES utilisateurs(id),
    nom_entreprise TEXT NOT NULL,
    logo_entreprise TEXT,
    poste TEXT,
    adresse TEXT,
    verifie BOOLEAN DEFAULT FALSE
);

-- Profil administrateur
CREATE TABLE profils_admin (
    utilisateur_id INT PRIMARY KEY REFERENCES utilisateurs(id),
    super_admin BOOLEAN DEFAULT FALSE
);

----------------------------------------
-- 2️⃣ CV & PARSING IA
----------------------------------------

CREATE TABLE cv (
    id SERIAL PRIMARY KEY,
    candidat_id INT REFERENCES profils_candidat(utilisateur_id),
    fichier_url TEXT NOT NULL,
    texte_brut TEXT,
    parse BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMPTZ DEFAULT now(),
    updated_at TIMESTAMPTZ DEFAULT now(),
    actif BOOLEAN DEFAULT FALSE
);

CREATE TABLE cv_competences (
    id SERIAL PRIMARY KEY,
    cv_id INT REFERENCES cv(id),
    nom_competence TEXT,
    confiance NUMERIC(5,2)
);

CREATE TABLE cv_formation (
    id SERIAL PRIMARY KEY,
    cv_id INT REFERENCES cv(id),
    ecole TEXT,
    diplome TEXT,
    annee_debut INT,
    annee_fin INT
);

CREATE TABLE cv_experience (
    id SERIAL PRIMARY KEY,
    cv_id INT REFERENCES cv(id),
    entreprise TEXT,
    poste TEXT,
    date_debut DATE,
    date_fin DATE,
    description TEXT
);

----------------------------------------
-- 3️⃣ OFFRES D'EMPLOI
----------------------------------------

CREATE TABLE offres_emploi (
    id SERIAL PRIMARY KEY,
    recruteur_id INT REFERENCES profils_recruteur(utilisateur_id),
    titre TEXT NOT NULL,
    description TEXT NOT NULL,
    localisation TEXT,
    salaire_min INT,
    salaire_max INT,
    niveau_seniorite TEXT,
    statut TEXT CHECK (statut IN ('BROUILLON','PUBLIE','FERME')) DEFAULT 'BROUILLON',
    created_at TIMESTAMPTZ DEFAULT now(),
    updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE competences_requises (
    id SERIAL PRIMARY KEY,
    offre_id INT REFERENCES offres_emploi(id),
    nom_competence TEXT
);

----------------------------------------
-- 4️⃣ EMBEDDINGS IA
----------------------------------------

CREATE TABLE embeddings_candidat (
    candidat_id INT PRIMARY KEY REFERENCES profils_candidat(utilisateur_id),
    vecteur vector(1536)
);

CREATE TABLE embeddings_offre (
    offre_id INT PRIMARY KEY REFERENCES offres_emploi(id),
    vecteur vector(1536)
);

----------------------------------------
-- 5️⃣ CANDIDATURES
----------------------------------------

CREATE TABLE candidatures (
    id SERIAL PRIMARY KEY,
    offre_id INT REFERENCES offres_emploi(id),
    candidat_id INT REFERENCES profils_candidat(utilisateur_id),
    cv_url TEXT,
    lettre_motivation TEXT,
    statut TEXT CHECK (statut IN ('POSTULE','REVU','ENTRETIEN','EMBAUCHE','REJETE')) DEFAULT 'POSTULE',
    updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE historique_statut_candidature (
    id SERIAL PRIMARY KEY,
    candidature_id INT REFERENCES candidatures(id),
    ancien_statut TEXT,
    nouveau_statut TEXT,
    updated_at TIMESTAMPTZ DEFAULT now()
);

----------------------------------------
-- 6️⃣ MESSAGERIE INTERNE
----------------------------------------

CREATE TABLE conversations (
    id SERIAL PRIMARY KEY,
    created_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE participants_conversation (
    id SERIAL PRIMARY KEY,
    conversation_id INT REFERENCES conversations(id),
    utilisateur_id INT REFERENCES utilisateurs(id)
);

CREATE TABLE messages (
    id SERIAL PRIMARY KEY,
    conversation_id INT REFERENCES conversations(id),
    expediteur_id INT REFERENCES utilisateurs(id),
    contenu TEXT NOT NULL,
    created_at TIMESTAMPTZ DEFAULT now(),
    lu BOOLEAN DEFAULT FALSE
);

----------------------------------------
-- 7️⃣ SUIVI ET ANALYTICS
----------------------------------------

CREATE TABLE vues_offres (
    id SERIAL PRIMARY KEY,
    offre_id INT REFERENCES offres_emploi(id),
    candidat_id INT REFERENCES profils_candidat(utilisateur_id),
    vu_le TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE interactions_candidat (
    id SERIAL PRIMARY KEY,
    candidat_id INT REFERENCES profils_candidat(utilisateur_id),
    offre_id INT REFERENCES offres_emploi(id),
    type TEXT CHECK (type IN ('CLIC','SAUVEGARDE','REJET')),
    created_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE actions_admin (
    id SERIAL PRIMARY KEY,
    admin_id INT REFERENCES profils_admin(utilisateur_id),
    action TEXT,
    details TEXT,
    created_at TIMESTAMPTZ DEFAULT now()
);

----------------------------------------
-- 8️⃣ COMPETENCES & PARAMETRES
----------------------------------------

CREATE TABLE competences_reference (
    id SERIAL PRIMARY KEY,
    nom TEXT UNIQUE NOT NULL
);

CREATE TABLE parametres_plateforme (
    id SERIAL PRIMARY KEY,
    cle TEXT UNIQUE NOT NULL,
    valeur JSONB
);


. la table utilisateur est la table de stockage des information des utilisateurs

. profil_condidat, profil_recruteur, profil_admin renferme les info specifique des utilisateur (au lieu de tous mettre dans un seul table la table utilisateur)

. la table competences_reference sert a normaliser la liste des competances connus par le plateforme :

    Rôle :
    C’est une liste globale de toutes les compétences possibles que la plateforme connaît.

    Sert à normaliser les compétences pour éviter les doublons (ex. “JavaScript” vs “JS”).

    Utilisée par :

    cv_competences pour les CV des candidats

    competences_requises pour les offres d’emploi

    Avantage : facilite les filtres, recherches, et matching IA.

. la table parametres_plateforme sert a stocker les parametre configurable du plateform pa exemple :

    Rôle :
    Stocke des paramètres configurables de la plateforme, par exemple :

    Poids des compétences pour le matching IA

    Seuil de similarité minimum pour recommander une offre

    Limites ou quotas d’utilisation

    Avantage : tu peux changer le comportement de l’IA ou du site sans toucher au code.

. la table vues_offres sert a enregistrer a chaque fois qu'un condidat consulte un offre , sert principalement pour la suggestion des offres pour les condidats en fonctions des offres qu'il consulte le plus et permet aussi au recruteur de consulter qu'elle condidat a consulter leur offre

. la table interactions_candidat sert a enregistrer les actions des condidats sur un offre par exemple il a cliquer sur un offre ou sauvegarder un offre ou rejeter un offre non interresser par l'offre en question pour faciliter aussi les suggestions des offres fait par l'IA plus tard

. la table actions_admin :

  Rôle :
    Permet de tracer les actions des administrateurs, par exemple :

    Validation d’un recruteur

    Suppression d’un CV ou d’un compte

    Modifications sur les offres

    Avantage : audit, sécurité et suivi des activités.

. les tables embeddings_candidat et embeddings_offre :

  Rôle :

    Stockent les vecteurs numériques représentant les CV et les offres.

    Ces vecteurs sont générés par l’IA (ex: OpenAI embeddings ou un modèle BERT).

    Permettent de calculer la similarité candidat ↔ offre pour les recommandations.

    Pourquoi pgvector ?

    PostgreSQL n’a pas nativement de type “vecteur” pour les calculs de similarité.

    L’extension pgvector ajoute ce type et des fonctions pour :

    cosine_similarity(vecteur1, vecteur2)

    l2_distance(vecteur1, vecteur2)

    Rechercher les vecteurs les plus proches pour recommander des offres aux candidats ou inversement.

. le tables cv_competences, cv_formation, cv_experience :

    cv_competences → compétences détectées dans le CV avec score de confiance

    cv_formation → diplômes et années de formation

    cv_experience → expériences pro, poste, entreprise, description

    Rôle :

    Permet à l’IA de comprendre le profil du candidat et de calculer des recommandations.

    Sert aussi pour le matching des compétences et l’affichage du profil candidat.

