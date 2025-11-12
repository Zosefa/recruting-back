<?php

namespace App\Controller\Admin;

use App\Entity\Personne;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

#[Route('/api/personnes')]
#[OA\Tag(name: 'Personnes')]
class PersonneController extends AbstractController
{
    #[OA\Get(
        summary: "Liste toutes les personnes",
        description: "Retourne la liste de toutes les personnes enregistrées",
        tags: ["Personnes"]
    )]
    #[OA\Response(
        response: 200,
        description: "Liste des personnes récupérée avec succès",
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'id', type: 'integer'),
                    new OA\Property(property: 'nom', type: 'string'),
                    new OA\Property(property: 'prenom', type: 'string'),
                    new OA\Property(property: 'email', type: 'string')
                ]
            )
        )
    )]
    #[Route('', name: 'api_personnes_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $personnes = $entityManager->getRepository(Personne::class)->findAll();

        $data = $serializer->serialize($personnes, 'json', ['groups' => 'personne:read']);

        return new JsonResponse($data, 200, [], true);
    }

    #[OA\Post(
        summary: "Crée une nouvelle personne",
        description: "Crée une nouvelle personne avec les données fournies",
        tags: ["Personnes"]
    )]
    #[OA\RequestBody(
        description: "Données de la personne à créer",
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'nom', type: 'string', example: "Dupont"),
                new OA\Property(property: 'prenom', type: 'string', example: "Jean"),
                new OA\Property(property: 'email', type: 'string', example: "jean.dupont@example.com")
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Personne créée avec succès",
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'nom', type: 'string'),
                new OA\Property(property: 'prenom', type: 'string'),
                new OA\Property(property: 'email', type: 'string')
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: "Données invalides"
    )]
    #[Route('', name: 'api_personnes_create', methods: ['POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse {
        $personne = $serializer->deserialize($request->getContent(), Personne::class, 'json', [
            'groups' => ['personne:write']
        ]);

        $errors = $validator->validate($personne);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $entityManager->persist($personne);
        $entityManager->flush();

        $data = $serializer->serialize($personne, 'json', ['groups' => 'personne:read']);

        return new JsonResponse($data, 201, [], true);
    }

    #[OA\Get(
        summary: "Récupère une personne par son ID",
        description: "Retourne les détails d'une personne spécifique",
        tags: ["Personnes"]
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'ID de la personne',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: "Personne trouvée",
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'nom', type: 'string'),
                new OA\Property(property: 'prenom', type: 'string'),
                new OA\Property(property: 'email', type: 'string')
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Personne non trouvée"
    )]
    #[Route('/{id}', name: 'api_personnes_show', methods: ['GET'])]
    public function show(Personne $personne, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($personne, 'json', ['groups' => 'personne:read']);

        return new JsonResponse($data, 200, [], true);
    }

    #[OA\Put(
        summary: "Met à jour une personne",
        description: "Met à jour les informations d'une personne existante",
        tags: ["Personnes"]
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'ID de la personne à mettre à jour',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        description: "Nouvelles données de la personne",
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'nom', type: 'string', example: "Martin"),
                new OA\Property(property: 'prenom', type: 'string', example: "Marie"),
                new OA\Property(property: 'email', type: 'string', example: "marie.martin@example.com")
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Personne mise à jour avec succès"
    )]
    #[OA\Response(
        response: 400,
        description: "Données invalides"
    )]
    #[OA\Response(
        response: 404,
        description: "Personne non trouvée"
    )]
    #[Route('/{id}', name: 'api_personnes_update', methods: ['PUT'])]
    public function update(
        Personne $personne,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse {
        $serializer->deserialize($request->getContent(), Personne::class, 'json', [
            'groups' => ['personne:write'],
            'object_to_populate' => $personne
        ]);

        $errors = $validator->validate($personne);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $entityManager->flush();

        $data = $serializer->serialize($personne, 'json', ['groups' => 'personne:read']);

        return new JsonResponse($data, 200, [], true);
    }

    #[OA\Delete(
        summary: "Supprime une personne",
        description: "Supprime une personne de la base de données",
        tags: ["Personnes"]
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'ID de la personne à supprimer',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 204,
        description: "Personne supprimée avec succès"
    )]
    #[OA\Response(
        response: 404,
        description: "Personne non trouvée"
    )]
    #[Route('/{id}', name: 'api_personnes_delete', methods: ['DELETE'])]
    public function delete(Personne $personne, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($personne);
        $entityManager->flush();

        return new JsonResponse(null, 204);
    }
}
