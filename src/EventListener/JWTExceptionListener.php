<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JWTExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_authentication_failure' => 'onAuthenticationFailure',
            'lexik_jwt_authentication.on_jwt_not_found' => 'onJWTNotFound',
            'lexik_jwt_authentication.on_jwt_invalid' => 'onJWTInvalid',
            'lexik_jwt_authentication.on_jwt_expired' => 'onJWTExpired',
        ];
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $response = new JsonResponse([
            'code' => 401,
            'message' => 'Veuilliz bien verifier vos information (email et mot de passe)'
        ], 401);

        $event->setResponse($response);
    }

    public function onJWTNotFound(JWTNotFoundEvent $event): void
    {
        $response = new JsonResponse([
            'code' => 401,
            'message' => 'Access Token non trouvé'
        ], 401);

        $event->setResponse($response);
    }

    public function onJWTInvalid(JWTInvalidEvent $event): void
    {
        $response = new JsonResponse([
            'code' => 401,
            'message' => 'Access Token Invalide'
        ], 401);

        $event->setResponse($response);
    }

    public function onJWTExpired(JWTExpiredEvent $event): void
    {
        $response = new JsonResponse([
            'code' => 401,
            'message' => 'Access Token éxpiré'
        ], 401);

        $event->setResponse($response);
    }
}
