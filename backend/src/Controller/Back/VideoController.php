<?php

namespace App\Controller\Back;

use App\Entity\ChoreographyPart;
use App\Entity\Move;
use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back")
 */
class VideoController extends AbstractController
{
    /**
     * @Route("/move/{id}/video/new", name="app_back_move_video_new", methods={"GET", "POST"})
     */
    public function newMove(Request $request, VideoRepository $videoRepository, Move $move): Response
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video->setMove($move);
            $videoRepository->add($video, true);
            if($move->getSchool()) {
                return $this->redirectToRoute('app_back_move_school_edit', [
                    'id' => $move->getId(),
                ], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_back_move_edit', [
                    'id' => $move->getId(),
                ], Response::HTTP_SEE_OTHER);
            }            
        }

        return $this->renderForm('back/video/new_move.html.twig', [
            'video' => $video,
            'form' => $form,
            'move' => $move,
        ]);
    }

    /**
     * @Route("/move/{id}/video/edit", name="app_back_video_edit", methods={"GET", "POST"})
     */
    public function editMove(Request $request, Video $video, VideoRepository $videoRepository): Response
    {
        $this->denyAccessUnlessGranted('VIDEO_EDIT', $video);
        $move = $video->getMove();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video->setUpdatedAt(new DateTimeImmutable());
            $videoRepository->add($video, true);

            if($move->getSchool()) {
                return $this->redirectToRoute('app_back_move_school_edit', [
                    'id' => $move->getId(),
                ], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_back_move_edit', [
                    'id' => $move->getId(),
                ], Response::HTTP_SEE_OTHER);
            }    
        }

        return $this->renderForm('back/video/edit_move.html.twig', [
            'video' => $video,
            'form' => $form,
            'move' => $move,
        ]);
    }

    /**
     * @Route("/move/video/{id}", name="app_back_video_delete", methods={"POST"})
     */
    public function deleteMove(Request $request, Video $video, VideoRepository $videoRepository): Response
    {
        $this->denyAccessUnlessGranted('VIDEO_EDIT', $video);
        $move = $video->getMove();
        if ($this->isCsrfTokenValid('delete'.$video->getId(), $request->request->get('_token'))) {
            $videoRepository->remove($video, true);
        }

        $this->addFlash('success', 'Video supprimé');

        return $this->redirectToRoute('app_back_move_edit', [
            'id' => $move->getId(),
        ], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/choreography/part/{id}/video/new", name="app_back_choreography_part_video_new", methods={"GET", "POST"})
     */
    public function newChore(Request $request, VideoRepository $videoRepository, ChoreographyPart $choreoPart): Response
    {
        $this->denyAccessUnlessGranted('CHOREOGRAPHY_PART_EDIT', $choreoPart);
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video->setChoreographyPart($choreoPart);
            $videoRepository->add($video, true);

            return $this->redirectToRoute('app_back_choreography_part_edit', [
                'id' => $choreoPart->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/video/new_chore.html.twig', [
            'video' => $video,
            'form' => $form,
            'choreography_part' => $choreoPart,
        ]);
    }

    /**
     * @Route("/choreography/part/{id}/video/edit", name="app_back_choreography_part_video_edit", methods={"GET", "POST"})
     */
    public function editChore(Request $request, Video $video, VideoRepository $videoRepository): Response
    {
        $this->denyAccessUnlessGranted('VIDEO_EDIT', $video);
        $choreoPart = $video->getChoreographyPart();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video->setUpdatedAt(new DateTimeImmutable());
            $videoRepository->add($video, true);

            return $this->redirectToRoute('app_back_choreography_part_edit', [
                'id' => $choreoPart->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/video/edit_chore.html.twig', [
            'video' => $video,
            'form' => $form,
            'choreographyPart' => $choreoPart,
        ]);
    }

    /**
     * @Route("/choreography/part/video/{id}", name="app_back_choreography_part_video_delete", methods={"POST"})
     */
    public function deleteChore(Request $request, Video $video, VideoRepository $videoRepository): Response
    {
        $this->denyAccessUnlessGranted('VIDEO_EDIT', $video);
        $choreoPart = $video->getChoreographyPart();
        if ($this->isCsrfTokenValid('delete'.$video->getId(), $request->request->get('_token'))) {
            $videoRepository->remove($video, true);
        }

        $this->addFlash('success', 'Video supprimé');

        return $this->redirectToRoute('app_back_choreography_part_edit', [
            'id' => $choreoPart->getId(),
        ], Response::HTTP_SEE_OTHER);
    }
}
