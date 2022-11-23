<?php

namespace App\Controller\Back;

use DateTimeImmutable;
use App\Entity\Choreography;
use App\Entity\ChoreographyPart;
use App\Form\ChoreographyPartType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ChoreographyPartRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/choreography/part")
 */
class ChoreographyPartController extends AbstractController
{
    
    /**
     * @Route("/{id}/new", name="app_back_choreography_part_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ChoreographyPartRepository $choreographyPartRepository, Choreography $choreography): Response
    {
        $this->denyAccessUnlessGranted('CHOREOGRAPHY_EDIT', $choreography);
        $choreographyPart = new ChoreographyPart();
        $form = $this->createForm(ChoreographyPartType::class, $choreographyPart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $choreographyPart->setChoreography($choreography);
            $choreographyPartRepository->add($choreographyPart, true);

            return $this->redirectToRoute('app_back_choreography_edit', [
                'id' => $choreography->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/choreography_part/new.html.twig', [
            'choreography_part' => $choreographyPart,
            'form' => $form,
            'choreography' => $choreography,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_choreography_part_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ChoreographyPart $choreographyPart, ChoreographyPartRepository $choreographyPartRepository): Response
    {
        $this->denyAccessUnlessGranted('CHOREOGRAPHY_PART_EDIT', $choreographyPart);
        $choreo = $choreographyPart->getChoreography();
        $videos = $choreographyPart->getVideos();
        $form = $this->createForm(ChoreographyPartType::class, $choreographyPart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $choreographyPart->setUpdatedAt(new DateTimeImmutable());
            $choreographyPartRepository->add($choreographyPart, true);

            return $this->redirectToRoute('app_back_choreography_edit', [
                'id' => $choreo->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/choreography_part/edit.html.twig', [
            'choreography_part' => $choreographyPart,
            'form' => $form,
            'choreo' => $choreo,
            'videos' => $videos,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_choreography_part_delete", methods={"POST"})
     */
    public function delete(Request $request, ChoreographyPart $choreographyPart, ChoreographyPartRepository $choreographyPartRepository): Response
    {
        $this->denyAccessUnlessGranted('CHOREOGRAPHY_PART_EDIT', $choreographyPart);
        $choreo = $choreographyPart->getChoreography();
        if ($this->isCsrfTokenValid('delete'.$choreographyPart->getId(), $request->request->get('_token'))) {
            $choreographyPartRepository->remove($choreographyPart, true);
        }

        $this->addFlash('success', 'Partie de la chorégraphie supprimée');

        return $this->redirectToRoute('app_back_choreography_edit', [
            'id' => $choreo->getId(),
        ], Response::HTTP_SEE_OTHER);
    }
}
