<?php

namespace App\Controller\Recruteur;

use App\DTO\ProfilRecruteur\ProfilRecruteurDTO;
use App\Service\Applicatif\ProfilRecruteurSA;
use App\Service\Applicatif\UtilisateurSA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

#[Route('/api/profil-recruteur')]
#[OA\Tag(name: 'profile Recruteur')]
class ProfilRecruteurController extends AbstractController
{
    public function __construct(
        private ProfilRecruteurSA $profilRecruteurSA,
        private SerializerInterface $serializer,
        private UtilisateurSA $utilisateurSA
    )
    {}

    #[Route('', name: 'recruting_profil_recruteur_create', methods: ['POST'])]
    public function create(Request $request)
    {
        try {
            $utilisateurId = $request->request->get('utilisateurId');
            $nomEntreprise = $request->request->get('nomEntreprise');
            $poste = $request->request->get('poste');
            $adresse = $request->request->get('adresse');
            $verifie = filter_var($request->request->get('verifie'), FILTER_VALIDATE_BOOLEAN);
            $logoFile = $request->files->get('logoEntreprise');

            if (!$logoFile) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Aucun logo reçu'
                ], 400);
            }
            // $logoFile = $request->files->get('logoEntreprise');
            $utilisateur = $this->utilisateurSA->findById((int) $utilisateurId);

            if(!$utilisateur)
            {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Utilisateur non trouvé !'
                ], 404);
            }

            $nomFichierLogo = $logoFile->getClientOriginalName();

            $dto = new ProfilRecruteurDTO(
                $utilisateur,
                $nomEntreprise,
                $nomFichierLogo,
                $poste,
                $adresse,
                $verifie
            );

            $profilRecruteur = $this->profilRecruteurSA->create($dto);

            if(is_object($profilRecruteur))
            {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Le profil de l\'utilisateur a été créer avec succèss!',
                    'data' => json_decode($this->serializer->serialize($profilRecruteur, 'json', ['groups' => 'profil_condidat:read']))
                ], 201);
            }else{
                if($profilRecruteur['success'] === false)
                {
                    return new JsonResponse([
                        'success' => false,
                        'message' => $profilRecruteur['msg']
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
}
