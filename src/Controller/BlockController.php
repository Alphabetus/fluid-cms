<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BlockController
{
    /**
     * @return JsonResponse
     * @Route("/blocks/add", name="blocks.add")
     */
    public function add(): JsonResponse
    {
        return new JsonResponse("OK", 200);
    }
}