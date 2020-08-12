<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RootController extends AbstractController
{
    /**
     * @return Response
     * @Route("/", name="root")
     */
    public function root(): Response
    {
        return $this->render("root/root.html.twig");
    }
}