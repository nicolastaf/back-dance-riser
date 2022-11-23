<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Member;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\LevelRepository;
use App\Repository\MemberRepository;
use App\Service\Registration;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{
    /**
     * @Route("/api/users/{id}/level", name="app_api_users_level_get_item", methods={"GET"})
     */
    public function getItemLevel(User $user = null): JsonResponse
    {
        if (null === $user) {
            return $this->json(['message' => 'Cet utilisateur n\'existe pas'], Response::HTTP_NOT_FOUND);
        }
        $level = $user->getLevel();
        
        return $this->json($level, Response::HTTP_OK, [], ['groups' => 'api_users_get_item']);
    }

    /**
     * @Route("/api/users/{id}/school", name="app_api_users_school_get_item", methods={"GET"})
     */
    public function getItemSchool(User $user = null, MemberRepository $memberRepository): JsonResponse
    {
        if (null === $user) {
            return $this->json(['message' => 'Cet utilisateur n\'existe pas'], Response::HTTP_NOT_FOUND);
        }
        $member = $memberRepository->findSchoolByUserIfActivated($user);
        
        return $this->json($member, Response::HTTP_OK, [], ['groups' => 'api_users_get_item']);
    }

     /**
     * @Route("/api/subscribe", name="app_api_subscribe", methods={"POST"})
     */
    public function subscribe(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validatorInterface,
        UserPasswordHasherInterface $passwordHasher,
        LevelRepository $levelRepository,
        Registration $registration
    ) {
        $jsonContent = $request->getContent();
        
        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        $pro = $levelRepository->findOneBy(['name' => 'Pro']);

        $avatar = "data:image/png;base64,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";
        if (empty($user->getAvatar())) {
            $user->setAvatar($avatar);
        }
        
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setActivated(false);
        $user->setLevel($pro);
        $user->setRoles(['ROLE_MEMBER']);
        
        $errors = $validatorInterface->validate($user);

        if (count($errors) > 0) {
            $errorsClean = [];

            /** @var ConstraintViolation $error L'erreur */
            foreach($errors as $error) {
                $errorsClean[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json([
                'errors' => $errorsClean,
            ], 
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $manager = $doctrine->getManager();
        $manager->persist($user);
        $manager->flush(); 
        
        $registration->register($user);

        return $this->json(
            $user,
            Response::HTTP_CREATED,
            [],
            ['groups' => 'api_users_get_item']
        );
    }

    /**
     * @Route("/api/users/{id}", name="app_api_user_patch_item", methods={"PATCH"})
     */
    public function patchItem(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator,
        User $user = null
    ) {
        // 404 ?
        if (null === $user) {
            throw $this->createNotFoundException('User non trouvé');
        }
        $jsonContent = $request->getContent();

        $serializer->deserialize(
            $jsonContent,
            User::class,
            'json',
            // on désérialise le JSON dans l'objet $movie qui vient de la BDD
            // @see https://symfony.com/doc/5.4/components/serializer.html#deserializing-in-an-existing-object
            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
        );

        $errors = $validator->validate($user);

        if (count($errors) > 0) {

            $errorsClean = [];

            /** @var ConstraintViolation $error L'erreur */
            foreach ($errors as $error) {
                // on pousse l'erreur à la clé qui correspond à la propriété en erreur
                $errorsClean[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json([
                'errors' => $errorsClean
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $manager = $doctrine->getManager();
        $manager->flush();

        // status 200 et rien de plus
        return $this->json(['message' => 'Utilisateur a bien été modifié'], Response::HTTP_OK);
    }
}
