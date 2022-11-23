<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\MakerBundle\EventRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    /**
     * @Route("/api/events", name="app_api_events_collection", methods={"GET"})
     */
    public function getCollection(EventRepository $eventRepository): JsonResponse
    {
        $events = $eventRepository->findFutureEvents();
        $formattedDates = [];
        foreach ($events as $event) {
            $id = $event->getId();
            $date = $event->getDate();
            $formattedDate = date_format($date, 'd-m-Y H:i');
            $formattedDates[$id] = $formattedDate;
        }
        return $this->json(['events' => $events, 'dates' => $formattedDates], Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], ['groups' => 'api_events_get_collection']);
    }

    /**
     * @Route("/api/events/{slug}/{id}", name="app_api_event_get_item", methods={"GET"})
     */
    public function getItem(Event $event = null): JsonResponse
    {
        $formattedDates = [];
        $id = $event->getId();
        $date = $event->getDate();
        $formattedDate = date_format($date, 'd-m-Y H:i');
        $formattedDates[$id] = $formattedDate;
        if (null === $event) {
            return $this->json(['message' => 'Cet événement n\'existe pas'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['events' => $event, 'dates' => $formattedDates], Response::HTTP_OK, [], ['groups' => 'api_events_get_item']);
    }
}
