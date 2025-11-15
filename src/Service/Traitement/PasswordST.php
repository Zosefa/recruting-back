<?php

namespace App\Service\Traitement;

use App\Entity\Utilisateur;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordST
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasherInterface
    )
    {}

    public function hashPassword(Utilisateur $user, string $password)
    {
        return $this->userPasswordHasherInterface->hashPassword($user, $password);
    }

    public function generatePassword(int $size = 10): string
    {
        $charSets = ['a-z', 'A-Z', '0-9', '#'];
        $possibleChars = '';
        $charSetMap = [
            'a-z' => 'abcdefghijklmnopqrstuvwxyz',
            'A-Z' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            '0-9' => '0123456789',
            '#' => '![]{}()%&*$#^<>~@|'
        ];

        foreach ($charSets as $set) {
            $possibleChars .= $charSetMap[$set] ?? '';
        }

        return implode('', array_map(
            fn() => $possibleChars[random_int(0, strlen($possibleChars) - 1)],
            range(1, $size)
        ));
    }
}
