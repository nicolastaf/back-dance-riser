<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class SecurityController extends AbstractController
{
     /**
     * @Route("/api/change-password/{id}", name="app_api_change_password", methods={"PATCH"})
     */
    public function changePassword(
        User $user,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validatorInterface,
        UserPasswordHasherInterface $passwordHasher
    ) {
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

        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        
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

        return $this->json(
            $user,
            Response::HTTP_CREATED,
            [],
            ['groups' => 'api_users_get_item']
        );
    }
}
