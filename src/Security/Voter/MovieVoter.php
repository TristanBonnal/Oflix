<?php

namespace App\Security\Voter;

use Symfony\Bundle\WebProfilerBundle\Csp\NonceGenerator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // si l'attribut commence par MOVIE_ alors on veut voter
        if (substr($attribute, 0, 6) === "MOVIE_")
        {
            return true;
        }

        return false;

    }

    /**
     * Undocumented function
     *
     * @param string $attribute
     * @param Movie $subject
     * @param TokenInterface $token
     * @return boolean
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /* @var User $user */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // on ne peut modifier que le film qui a le nom schrek 
        // que si on est l'utilisateur manager@manager.com
        if ($subject->getTitle() === 'Schrek' && $user->getUserIdentifier() !== 'manager@manager.com')
        {
            return false;
        }

        return true;
    }
}
