<?php

namespace App\Controller\Back;

use App\Entity\Member;
use App\Entity\User;
use App\Form\UserLevelType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/level")
 */
class LevelController extends AbstractController
{
    /**
     * @Route("/user/{id}/edit", name="app_back_level_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Member $member, UserRepository $userRepository): Response
    {
        $user = $member->getUser();
        $school = $member->getSchool();
        $form = $this->createForm(UserLevelType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_back_member', [
            'id' => $school->getId(),
            ], Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('back/level/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'school' => $school,
        ]);
    }
}
