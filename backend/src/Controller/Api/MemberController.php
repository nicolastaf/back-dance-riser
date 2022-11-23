<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Member;
use App\Entity\School;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MemberController extends AbstractController
{
    /**
     * @Route("/api/newMember", name="app_api_member_post_item", methods={"POST"})
     */
    public function postItem(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine, ValidatorInterface $validatorInterface)
    {
        // @see https://symfony.com/doc/5.4/components/serializer.html
        $jsonContent = $request->getContent();

        $member = $serializer->deserialize($jsonContent, Member::class, 'json');
        
        $errors = $validatorInterface->validate($member);

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

        $member->setActivated(false);
        $member->setNewRequest(true);
        $manager = $doctrine->getManager();
        $manager->persist($member);
        $manager->flush();

        return $this->json(
            $member,
            Response::HTTP_CREATED,
            [],
            ['groups' => 'app_api_member_post_item']
        ); 
    }
}
