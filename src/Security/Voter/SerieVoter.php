<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class SerieVoter extends Voter
{
    public const EDIT = 'SERIE_EDIT';
    public const VIEW = 'SERIE_VIEW';
    public const DELETE = 'SERIE_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\Serie;
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
            case self::EDIT:
                return \in_array('ROLE_ADMIN', $user->getRoles());
                break;
            case self::VIEW:
                return (!(strpos($subject->getGenres(), 'Crime') && $user->getBirthDate() > new \DateTime('-18 year')) || !strpos($subject->getGenres(), 'Crime') || \in_array('ROLE_ADMIN', $user->getRoles()));
            case self::DELETE:
                return \in_array('ROLE_ADMIN', $user->getRoles());
                break;
        }

        return false;
    }
}
