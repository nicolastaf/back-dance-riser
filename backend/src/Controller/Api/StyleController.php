<?php

namespace App\Controller\Api;

use App\Repository\StyleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StyleController extends AbstractController
{
    /**
     * @Route("/api/styles", name="app_api_styles", methods={"GET"})
     */
    public function getCollection(StyleRepository $styleRepository): JsonResponse
    {
        $styles = $styleRepository->findBy(['activated' => true]);
        return $this->json($styles, Response::HTTP_OK, ['Access-Control-Allow-Origin' => '*'], ['groups' => 'api_styles_get_collection']);
    }
}
