<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Member;
use App\Entity\School;
use App\Service\MySlugger;
use App\Repository\UserRepository;
use App\Repository\SchoolRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SchoolController extends AbstractController
{
    /**
     * @Route("/api/schools", name="app_api_schools_get_collection", methods={"GET"})
     */
    public function getCollection(SchoolRepository $schoolRepository): JsonResponse
    {
        $schools = $schoolRepository->findBy(['activated' => true]);
        return $this->json($schools, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], ['groups' => 'api_schools_get_collection']);
    }

    /**
     * @Route("/api/schools/{slug}/{id}", name="app_api_schools_get_item", methods={"GET"})
     */
    public function getItem(School $school = null): JsonResponse
    {
        if (null === $school) {
            return $this->json(['message' => 'Cette école n\'existe pas'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($school, Response::HTTP_OK, [], ['groups' => 'api_schools_get_item']);
    }

    /**
     * @Route("/api/newSchool", name="app_api_school_post_item", methods={"POST"})
     */
    public function postItem(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validatorInterface,
        MySlugger $slugger,
        TokenStorageInterface $storage,
        JWTTokenManagerInterface $jwtManager,
        UserRepository $userRepository
        ) 
    {
        $admins = $userRepository->findAllByRole(['ROLE_ADMIN']);
        // @see https://symfony.com/doc/5.4/components/serializer.html
        $jsonContent = $request->getContent();

        $school= $serializer->deserialize($jsonContent, School::class, 'json');

        $decodedJwtToken = $jwtManager->decode($storage->getToken());
        $user = $userRepository->findOneBy(['email' => $decodedJwtToken['username']]);
                
        $errors = $validatorInterface->validate($school);

        // y'a-t-il des erreurs ?
        if (count($errors) > 0) {
            $errorsClean = [];

            /** @var ConstraintViolation $error L'erreur */
            foreach($errors as $error) {
                // on pousse l'erreur à la clé qui correspond à la propriété en erreur
                $errorsClean[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json([
                'errors' => $errorsClean,
            ], 
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        if (!in_array("ROLE_ADMIN", $decodedJwtToken['roles'])) {
            $memberManager = new Member();
            $memberManager->setSchool($school);
            $memberManager->setUser($user);
            $memberManager->setActivated(true);
            $memberManager->setNewRequest(false);
            $doctrine->getManager()->persist($memberManager);
            $user->setRoles(['ROLE_MANAGER']);
            $doctrine->getManager()->persist($user);
        }

        foreach ($admins as $admin) {
            $member = new Member();
            $member->setSchool($school);
            $member->setUser($admin);
            $member->setActivated(true);
            $member->setNewRequest(false);
            $doctrine->getManager()->persist($member);
        }

        $school->setSlug($slugger->slugify($school->getName()));
        $school->setActivated(false);
        $school->setNewRequest(true);
        $manager = $doctrine->getManager();
        $manager->persist($school);
        $manager->flush();

        return $this->json(
            $school,
            Response::HTTP_CREATED,
            [],
            ['groups' => 'app_api_school_get_item']
        ); 
    }
}
