<?php

namespace App\Security\Voter;

use DateTimeImmutable;
use DateTimeZone;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AccessBackOfficeVoter extends Voter
{

    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    // on va definir toutes les actions possible à traiter par le voter
    public const ACCESS = 'BACK_OFFICE_ACCESS';

    protected function supports(string $action, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($action, [self::ACCESS]);
    }


    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::ACCESS:
                //  algo pour bloquer selon l'heure 
                $time = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
                $now = $time->format("H");
                // * uncomment for testing
                $now = 18;
                // s'il est plus de 17h ou moins de 9h on bloque le backoffice
                if ($now >= 17 || $now < 9) {
                    // si jamais on veut checker les droits d'accès d'un user dans un controller
                    return $this->authorizationChecker->isGranted("ROLE_ADMIN");
                }
                return true;
                break;
        }

        // ce return implique qu'à aucun moment dans la fonction on a return true et donc le voter va bloquer
        return false;
    }
}