<?php

namespace App\Controller\Back;

use App\Entity\Location;
use App\Entity\School;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/location")
 */
class LocationController extends AbstractController
{
    /**
     * @Route("school/{id}/new", name="app_back_location_new", methods={"GET", "POST"})
     */
    public function new(Request $request, LocationRepository $locationRepository, School $school): Response
    {
        $this->denyAccessUnlessGranted('SCHOOL_EDIT', $school);
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location->setSchool($school);
            $locationRepository->add($location, true);

            return $this->redirectToRoute('app_back_school_edit', [
                'id' => $school->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/location/new.html.twig', [
            'location' => $location,
            'form' => $form,
            'school' => $school,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_location_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Location $location, LocationRepository $locationRepository): Response
    {
        $this->denyAccessUnlessGranted('LOCATION_EDIT', $location);
        $school = $location->getSchool();
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location->setUpdatedAt(new DateTimeImmutable());
            $locationRepository->add($location, true);

            return $this->redirectToRoute('app_back_school_edit', [
                'id' => $school->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/location/edit.html.twig', [
            'location' => $location,
            'form' => $form,
            'school' => $school,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_location_delete", methods={"POST"})
     */
    public function delete(Request $request, Location $location, LocationRepository $locationRepository): Response
    {
        $this->denyAccessUnlessGranted('LOCATION_EDIT', $location);
        $school = $location->getSchool();
        if ($this->isCsrfTokenValid('delete'.$location->getId(), $request->request->get('_token'))) {
            $locationRepository->remove($location, true);
        }
        
        $this->addFlash('success', 'Lieu supprimé');

        return $this->redirectToRoute('app_back_school_edit', [
            'id' => $school->getId(),
        ], Response::HTTP_SEE_OTHER);
    }
}
