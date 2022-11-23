<?php

namespace App\Controller\Back;

use App\Entity\Member;
use App\Entity\School;
use App\Repository\MemberRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/member")
 */
class MemberController extends AbstractController
{
    /**
     * @Route("/school/{id}", name="app_back_member")
     */
    public function index(School $school, MemberRepository $memberRepository): Response
    {
        $this->denyAccessUnlessGranted('SCHOOL_EDIT', $school);
        $members = $memberRepository->findBy(['school' => $school]);
        $memberByRole = $memberRepository->findByMemberBySchool($school, ['ROLE_MANAGER', 'ROLE_MEMBER']);
        return $this->render('back/member/index.html.twig', [
            'school' => $school,
            'members' => $members,
            'members_by_role' => $memberByRole,
        ]);
    }

    /**
     * @Route("/switch/{id}", name="member_switch", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function switchMember(Member $member, Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('MEMBER_EDIT', $member);
        if ($member->isActivated()) {
            $member->setActivated(0);
        } else {
            $member->setActivated(1);
        }
        $em = $doctrine->getManager();
        $em->persist($member);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/accepted/{id}", name="member_accepted", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function acceptedMember(Member $member, Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('MEMBER_EDIT', $member);
        $member->setNewRequest(false);
        $member->setActivated(true);
        $em = $doctrine->getManager();
        $em->persist($member);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
