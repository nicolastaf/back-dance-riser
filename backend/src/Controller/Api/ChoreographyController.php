<?php

namespace App\Controller\Api;

use App\Entity\Style;
use App\Entity\Choreography;
use App\Repository\ChoreographyRepository;
use App\Repository\MemberRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChoreographyController extends AbstractController
{
    /**
     * @Route("/api/{slug}/choreography", name="app_api_choreography", methods={"GET"})
     */
    public function getCollection(
        ChoreographyRepository $choreographyRepository,
        TokenStorageInterface $storage,
        JWTTokenManagerInterface $jwtManager,
        Style $style,
        MemberRepository $memberRepository,
        UserRepository $userRepository
        ): JsonResponse
    {
        $decodedJwtToken = $jwtManager->decode($storage->getToken());
        $user = $userRepository->findOneBy(['email' => $decodedJwtToken['username']]);
        $members = $memberRepository->findSchoolByUserIfActivated($user);
        $schools = [];

        foreach($members as $member){
            $school = $member->getSchool()->getId();
            $schools[] = $school;
        }   

        $choreographies = $choreographyRepository->findByStyleBySchool($style, $schools);
        return $this->json($choreographies, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], ['groups' => 'api_choregraphies_get_collection']);
    }

    /**
     * @Route("/api/choreography/{slug}/{id}", name="app_api_choreography_get_item", methods={"GET"})
     */
    public function getItem(Choreography $choreography = null): JsonResponse
    {
        if (null === $choreography) {
            return $this->json(['message' => 'Cette chorégraphie n\'existe pas'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($choreography, Response::HTTP_OK, [], ['groups' => 'api_choreographies_get_item']);
    }
}
