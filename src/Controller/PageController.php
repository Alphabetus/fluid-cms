<?php


namespace App\Controller;


use App\Entity\Page;
use App\Form\NewPageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @return Response
     * @Route("/admin/pages/new", name="admin.pages.new")
     */
    public function new(): Response
    {
        $page = new Page();
        $form = $this->createForm(NewPageFormType::class, $page);
        return $this->render("page/new.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @return Response
     * @Route("/admin/pages/list", name="admin.pages.list")
     */
    public function list(): Response
    {
        return $this->render("page/list.html.twig");
    }
}