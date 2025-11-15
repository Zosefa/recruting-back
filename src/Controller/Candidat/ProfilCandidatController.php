<?php

namespace App\Controller\Candidat;

use App\DTO\ProfilCandidat\ProfilCandidatDTO;
use App\Entity\ProfilCandidat;
use App\Service\Applicatif\ProfilCandidatSA;
use App\Service\Applicatif\UtilisateurSA;
use ErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/api/profil-condidat')]
#[OA\Tag(name: 'profile Condidat')]
class ProfilCandidatController extends AbstractController
{
    public function __construct(
        private ProfilCandidatSA $profilCondidatSA,
        private SerializerInterface $serializer,
        private UtilisateurSA $utilisateurSA
    )
    {}

    #[Route('', name: 'recruting_profil_condidat_create', methods: ['POST'])]
    public function create(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $utilisateur = $this->utilisateurSA->findById((int) $data['utilisateurId']);

            if(!$utilisateur)
            {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Utilisateur non trouvé !'
                ], 404);
            }

            $dto = new ProfilCandidatDTO(
                $utilisateur,
                $data['nom'],
                $data['prenom'],
                $data['localisation'],
                $data['resume'],
                $data['anneExperience'],
                $data['profilCompleted']
            );

            $profilCondidat = $this->profilCondidatSA->create($dto);

            if(is_object($profilCondidat))
            {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Le profil de l\'utilisateur a été créer avec succèss!',
                    'data' => json_decode($this->serializer->serialize($profilCondidat, 'json', ['groups' => 'profil_condidat:read']))
                ], 201);
            }else{
                if($profilCondidat['success'] === false)
                {
                    return new JsonResponse([
                        'success' => false,
                        'message' => $profilCondidat['msg']
                    ], 500);
                }else{
                    return new JsonResponse([
                        'success' => false,
                        'message' => 'Erreur lors de la création du profil du compte'
                    ], 500);
                }
            }
        } catch (\Exception $ex) {
            return new JsonResponse([
                'success' => false,
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    #[Route('/{id}', name: 'recruting_profil_condidat_update', methods: ['PUT'])]
    public function update(int $id, Request $request)
    {
        try {
            $profilCandidat = $this->profilCondidatSA->findById($id);
            if(!$profilCandidat)
            {
                return new JsonResponse([
                    'success' => false,
                    'msg' => 'Aucun candidat trouvé pour l\'id : ' . $id
                ], 404);
            }

            $data = json_decode($request->getContent(), true);

            $utilisateur = $this->utilisateurSA->findById((int) $data['utilisateurId']);

            if(!$utilisateur)
            {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Utilisateur non trouvé !'
                ], 404);
            }

            $dto = new ProfilCandidatDTO(
                $utilisateur,
                $data['nom'],
                $data['prenom'],
                $data['localisation'],
                $data['resume'],
                $data['anneExperience'],
                $data['profilCompleted']
            );

            $profilCondidat = $this->profilCondidatSA->update($dto, $profilCandidat);

            if(is_object($profilCondidat))
            {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Le profil de l\'utilisateur a été modifié avec succèss!',
                    'data' => json_decode($this->serializer->serialize($profilCondidat, 'json', ['groups' => 'profil_condidat:read']))
                ], 201);
            }else{
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Erreur lors de la modification du profil du compte'
                ], 500);
            }

        } catch (\Exception $ex) {
            return new JsonResponse([
                'success' => false,
                'message' => $ex->getMessage()
            ], 500);
        }

    }


    #[Route('/{id}', name: 'recruting_profil_condidat_find_by_id', methods: ['GET'])]
    public function findById(int $id): JsonResponse
    {
        try {
            $profilCandidat = $this->profilCondidatSA->findById($id);
            if($profilCandidat)
            {
                return new JsonResponse([
                    'success' => true,
                    'compte' => json_decode($this->serializer->serialize($profilCandidat, 'json', ['groups' => 'profil_condidat:read']))
                ], 201);
            }else{
                return new JsonResponse([
                    'success' => false,
                    'msg' => 'Aucun candidat trouvé pour l\'id : ' . $id
                ], 404);
            }
        } catch (\Exception $ex) {
            return new JsonResponse([
                'success' => false,
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    #[Route('/check/nom-or-prenom', name: 'recruting_profil_condidat_check_by_nom_or_prenmo', methods: ['GET'])]
    public function findByNomOrPrenom(Request $request)
    {
        try {
            $critere = $request->query->get('critere');

            if (!$critere) {
                return new JsonResponse([
                    'success' => false,
                    'msg' => 'Paramètre "critere" manquant'
                ], 400);
            }

            $profilCandidats = $this->profilCondidatSA->findByNomOrPrenom($critere);

            if (!empty($profilCandidats)) {
                return new JsonResponse([
                    'success' => true,
                    'compte' => json_decode($this->serializer->serialize(
                        $profilCandidats,
                        'json',
                        ['groups' => 'profil_condidat:read']
                    ))
                ], 200);
            } else {
                return new JsonResponse([
                    'success' => false,
                    'msg' => 'Aucune personne trouvée !'
                ], 404);
            }

        } catch (\Exception $ex) {
            return new JsonResponse([
                'success' => false,
                'message' => $ex->getMessage()
            ], 500);
        }
    }

}
