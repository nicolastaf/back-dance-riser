<?php

namespace App\Controller\Api;

use App\Repository\LevelRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LevelController extends AbstractController
{
    /**
     * @Route("/api/levels", name="app_api_level")
     */
    public function getCollection(LevelRepository $levelRepository): JsonResponse
    {
        $levels = $levelRepository->findAll();
        return $this->json($levels, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], ['groups' => 'api_levels_get_collection']);
    }
}
