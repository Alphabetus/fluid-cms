<?php


namespace App\Controller;

use App\Entity\Log;
use App\Entity\GlobalSetting;
use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogfrontController extends AbstractController
{
    /**
     * @param string $slug
     * @return Response
     * @Route("/{slug}", name="blogfront.page", options={"expose"=true})
     */
    public function showPage(string $slug): Response
    {
        $page = $this->getDoctrine()->getRepository(Page::class)->findOneBy(["slug" => $slug]);
        if (!$page) {
            return $this->redirectToRoute("blogfront.not_found");
        }

        $nav_pages = $this->getDoctrine()->getRepository(Page::class)->findBy(["active" => true]);

        $blocks = $page->getBlocks();
        return $this->render("blogfront/show_page.html.twig", [
            "nav_pages" => $nav_pages,
            "blocks" => $blocks,
            "page" => $page
        ]);
    }

    /**
     * @return Response
     * @Route("/page/not-found", name="blogfront.not_found")
     */
    public function notFound(): Response
    {
        $nav_pages = $this->getDoctrine()->getRepository(Page::class)->findBy(["active" => true]);
        return $this->render("blogfront/404.html.twig", [
            "nav_pages" => $nav_pages
        ]);
    }

    /**
     * @return Response
     * @Route("/",name="root", options={"expose"=true})
     */
    public function root(): Response
    {
        $homepage_setting = $this->getDoctrine()->getRepository(GlobalSetting::class)->findOneBy(["name" => "homepage"])->getValue();
        $maintenanceStatus = $this->getDoctrine()->getRepository(GlobalSetting::class)->findOneBy(["name" => "maintenance"])->getValue();
        $pageRepository = $this->getDoctrine()->getRepository(Page::class);
        if ($homepage_setting == "") {
            return new Response("Configure your homepage in the Administration > Global Settings");
        }

        if ($maintenanceStatus == "true"){
            return $this->render("maintanence.html.twig");
        }

        $nav_pages = $this->getDoctrine()->getRepository(Page::class)->findBy(["active" => true]);

        $page = $pageRepository->findOneBy(["puid" => $homepage_setting]);

        if ($page && $page->getActive()) {
            $blocks = $page->getBlocks();
            return $this->render("blogfront/show_page.html.twig", [
                "nav_pages" => $nav_pages,
                "blocks" => $blocks,
                "page" => $page
            ]);
        } else {
            return new Response("Your homepage can not be found.");
        }

    }
}