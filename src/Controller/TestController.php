<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/api/test', methods:['GET'])]
    #[OA\Tag(name: 'Test')]
    #[OA\Get(
        path: "/api/test",
        summary: "Endpoint de test",
        description: "Cet endpoint permet de tester que l'API fonctionne correctement",
        operationId: "testEndpoint"
    )]
    #[OA\Response(
        response: 200,
        description: "Succès de la requête test",
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'msg', type: 'string', example: 'test controller')
            ]
        )
    )]
    public function index(): JsonResponse
    {
        return $this->json(['success' => true, 'msg' => 'test controller']);
    }
}
