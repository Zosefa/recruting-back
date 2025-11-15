<?php

namespace App\Controller\Security;

use App\DTO\Utilisateur\UtilisateurDTO;
use App\Entity\Utilisateur;
use App\Service\Applicatif\UtilisateurSA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

#[Route('/api/auth')]
#[OA\Tag(name: 'authentification')]
class AuthController extends AbstractController
{
    public function __construct(
        private UtilisateurSA $utilisateurSA
    )
    {}

    #[Route('/register', name: 'api_auth_register', methods: ['POST'])]
    public function register(
        Request $request,
        SerializerInterface $serializer
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $dto = new UtilisateurDTO(
            $data['email'],
            explode(',', $data['role']),
            $data['password'],
            true
        );

        $utilisateur = $this->utilisateurSA->create($dto);

        if(is_object($utilisateur))
        {
            return new JsonResponse([
                'success' => true,
                'message' => 'Utilisateur créer avec succèss',
                'user' => json_decode($serializer->serialize($utilisateur, 'json', ['groups' => 'utilisateur:read']))
            ], 201);
        }else{
            return new JsonResponse([
                'success' => false,
                'message' => 'Erreur lors de la création du compte'
            ], 500);
        }
    }

    #[Route('/login', name: 'api_auth_login', methods: ['POST'])]
    public function login(): JsonResponse
    {
        // Cette méthode ne sera jamais appelée directement
        // Le login est géré par le système de sécurité Symfony
        return $this->json([
            'message' => 'Use JSON login with email and password in request body'
        ], 400);
    }

    #[Route('/me', name: 'api_auth_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        assert($user instanceof Utilisateur);

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }
}
