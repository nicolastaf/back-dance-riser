<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Video;
use App\Repository\MemberRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class VideoVoter extends Voter
{
    private $memberRepository;

    private $security;

    public function __construct(MemberRepository $memberRepository, Security $security) {
        $this->memberRepository = $memberRepository;
        $this->security = $security;
    }

    /**
     * Décide si ce voter doit être executé
     */
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['VIDEO_EDIT'])
            && $subject instanceof Video;
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
        $video = $subject;
        if ($video->getChoreographyPart()) {
            $choreographyPart = $video->getChoreographyPart();
            $choreography = $choreographyPart->getChoreography();
            $school = $choreography->getSchool();
        } elseif ($video->getMove()) {
            $move = $video->getMove();
            $school = $move->getSchool();
        } else {
            $school = null;
        }
        $schoolMember = $this->memberRepository->findBy(['school' => $school]);
        $membersId = [];
        foreach ($schoolMember as $member) {
            $id = $member->getUser()->getId();
            $membersId[] = $id;
        }
        switch ($attribute) {
            case 'VIDEO_EDIT':
                if(in_array($userId, $membersId) || $this->security->isGranted('ROLE_ADMIN')){
                    return true;
                }
                break;
                return false;
        }

        return false;
    }
}