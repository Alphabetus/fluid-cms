<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlockController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/blocks/new", name="blocks.new")
     */
    public function createBlock(Request $request): JsonResponse
    {
        return new JsonResponse("WORKS", 200);
    }
}