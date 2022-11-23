<?php

namespace App\Controller\Api;

use App\Entity\CategoryMove;
use App\Entity\Move;
use App\Repository\MoveRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MoveController extends AbstractController
{
    /**
     * @Route("/api/{slug}/moves", name="app_api_moves_collection", methods={"GET"})
     */
    public function getCollection(MoveRepository $moveRepository, CategoryMove $categoryMove): JsonResponse
    {
        $moves = $moveRepository->findBy(['categoryMove' => $categoryMove]);
        return $this->json($moves, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], ['groups' => 'api_moves_get_collection']);
    }
    
    /**
     * @Route("/api/moves/{slug}/{id}", name="app_api_moves_get_item", methods={"GET"})
     */
    public function getItem(Move $move = null): JsonResponse
    {
        if (null === $move) {
            return $this->json(['message' => 'Ce mouvement n\'existe pas'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($move, Response::HTTP_OK, [], ['groups' => 'api_moves_get_item']);
    }
}
