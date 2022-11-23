<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Choreography;
use App\Repository\MemberRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ChoreographyVoter extends Voter
{
    private $memberRepository;

    public function __construct(MemberRepository $memberRepository) {
        $this->memberRepository = $memberRepository;
    }

    /**
     * Décide si ce voter doit être executé
     */
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['CHOREOGRAPHY_EDIT'])
            && $subject instanceof Choreography;
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
        $choreography = $subject;
        $school = $choreography->getSchool();
        $schoolMember = $this->memberRepository->findBy(['school' => $school]);
        $membersId = [];
        foreach ($schoolMember as $member) {
            $id = $member->getUser()->getId();
            $membersId[] = $id;
        }
        switch ($attribute) {
            case 'CHOREOGRAPHY_EDIT':
                if(in_array($userId, $membersId)){
                    return true;
                }
                break;
                return false;
        }

        return false;
    }
}