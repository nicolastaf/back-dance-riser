<?php

namespace App\Controller\Api;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/api/home", name="app_api_home", methods={"GET"})
     */
    public function getCollection(EventRepository $eventRepository): JsonResponse
    {
        $events = $eventRepository->findLastCreatedDate();
        $formattedDates = [];
        foreach ($events as $event) {
            $id = $event->getId();
            $date = $event->getDate();
            $formattedDate = date_format($date, 'd-m-Y H:i');
            $formattedDates[$id] = $formattedDate;
        }
        return $this->json(['events' => $events, 'dates' => $formattedDates], Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], ['groups' => 'api_home']);
    }
}
