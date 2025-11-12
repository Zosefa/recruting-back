<?php
// src/EventListener/ExceptionListener.php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // Ne pas gérer les exceptions JWT (déjà gérées par JWTExceptionListener)
        if (str_contains($exception->getMessage(), 'JWT') ||
            str_contains(get_class($exception), 'JWT')) {
            return;
        }

        $response = $this->createJsonResponse($exception);
        $event->setResponse($response);
    }

    private function createJsonResponse(\Throwable $exception): JsonResponse
    {
        // Déterminer le code HTTP et le message
        $statusCode = 500;
        $message = 'Erreur interne du serveur';

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $message = $exception->getMessage();
        }

        // Messages personnalisés par code HTTP
        switch ($statusCode) {
            case 400:
                $message = $exception->getMessage() ?: 'Requête invalide';
                break;
            case 403:
                $message = 'Accès non autorisé';
                break;
            case 404:
                $message = 'Ressource non trouvée';
                if ($exception instanceof NotFoundHttpException) {
                    $message = 'Endpoint non trouvé';
                }
                break;
            case 405:
                $message = 'Méthode non autorisée';
                break;
            case 422:
                $message = 'Données invalides';
                break;
            case 500:
                $message = 'Erreur interne du serveur';
                // En production, ne pas exposer les détails de l'erreur
                if ($_ENV['APP_ENV'] === 'prod') {
                    $message = 'Erreur interne du serveur';
                }
                break;
        }

        // Structure de réponse cohérente
        $data = [
            'code' => $statusCode,
            'message' => $message
        ];

        // Ajouter les détails de validation pour les erreurs 400/422
        if ($statusCode === 400 || $statusCode === 422) {
            $data['errors'] = $this->getValidationErrors($exception);
        }

        // En développement, ajouter plus de détails
        if ($_ENV['APP_ENV'] === 'dev') {
            $data['debug'] = [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ];
        }

        return new JsonResponse($data, $statusCode);
    }

    private function getValidationErrors(\Throwable $exception): array
    {
        $errors = [];

        // Récupérer les erreurs de validation Symfony
        if ($exception instanceof ValidationFailedException) {
            foreach ($exception->getViolations() as $violation) {
                $property = $violation->getPropertyPath();
                $errors[$property] = $violation->getMessage();
            }
        }

        return $errors;
    }
}
