<?php

namespace App\EventListener;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\LevelRepository;
use App\Repository\MemberRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener 
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $currentUser = $this->userRepository->findByEmail($user->getUserIdentifier());

        $data['email'] = $user->getUserIdentifier();
        $data['firstname'] = $currentUser[0]->getFirstname();
	    $data['lastname'] = $currentUser[0]->getLastName();
        $data['id'] = $currentUser[0]->getId();
	    $data['roles'] = $currentUser[0]->getRoles();
        $data['avatar'] = $currentUser[0]->getAvatar();
        $data['agendaLink'] = $currentUser[0]->getAgendaLink();
	    $data['blaze'] = $currentUser[0]->getBlaze();
	    $data['activated'] = $currentUser[0]->isActivated();

        $event->setData($data);
    }
}