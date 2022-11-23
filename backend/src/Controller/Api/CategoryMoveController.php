<?php

namespace App\Controller\Api;

use App\Entity\Style;
use App\Repository\CategoryMoveRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryMoveController extends AbstractController
{

    /**
     * @Route("/api/{slug}/category/moves", name="app_api_category_move_get_collection", methods={"GET"})
     */
    public function getCollection(CategoryMoveRepository $categoryMoveRepository, Style $style): JsonResponse
    {
        $catMoves = $categoryMoveRepository->findCatemoveByStyle($style);
        return $this->json($catMoves, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], ['groups' => 'api_category_moves_get_collection']);
    }
}
