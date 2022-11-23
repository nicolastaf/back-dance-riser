<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Event;
use App\Repository\MemberRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class EventVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Décide si ce voter doit être executé
     */
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EVENT_EDIT'])
            && $subject instanceof Event;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var User $user */
        $userId = $user->getId();
        $event = $subject;
        switch ($attribute) {
            case 'EVENT_EDIT':
                if($userId === $event->getCreatedBy()->getId() || $this->security->isGranted('ROLE_ADMIN')){
                    return true;
                }
                break;
                return false;
        }

        return false;
    }
}