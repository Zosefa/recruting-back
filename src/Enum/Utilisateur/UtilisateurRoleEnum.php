<?php

namespace App\Enum\Utilisateur;

enum UtilisateurRoleEnum: string
{
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_CONDIDAT = 'ROLE_CONDIDAT';
    case ROLE_RECRUITER = 'ROLE_RECRUITER';

    public function label():string
    {
        return match($this) {
            self::ROLE_ADMIN => 'Adminisatrateur',
            self::ROLE_CONDIDAT => 'Condidat',
            SELF::ROLE_RECRUITER => 'Récruteur'
        };
    }

    public static function roleChoice(): array
    {
        return array_combine(
            array_map(fn($case) => $case->value, self::cases()),
            array_map(fn($case) => $case->label(), self::cases())
        );
    }
}
