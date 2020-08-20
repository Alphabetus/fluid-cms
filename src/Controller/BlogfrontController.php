<?php


namespace App\Controller;


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
     * @Route("/{slug}", name="blogfront.page")
     */
    public function showPage(string $slug): Response
    {
        $page = $this->getDoctrine()->getRepository(Page::class)->findOneBy(["slug" => $slug]);
        if (!$page) {
            return $this->redirectToRoute("blogfront.not_found");
        }

        $blocks = $page->getBlocks();
        return $this->render("blogfront/show_page.html.twig", [
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
        return $this->render("blogfront/404.html.twig");
    }

    /**
     * @return Response
     * @Route("/", name="root")
     */
    public function root(): Response
    {
        $homepage_setting = $this->getDoctrine()->getRepository(GlobalSetting::class)->findOneBy(["name" => "homepage"])->getValue();
        $pageRepository = $this->getDoctrine()->getRepository(Page::class);
        if ($homepage_setting == "") {
            return new Response("Configure your homepage in the Administration > Global Settings");
        }

        $page = $pageRepository->findOneBy(["puid" => $homepage_setting]);

        if ($page) {
            $blocks = $page->getBlocks();
            return $this->render("blogfront/show_page.html.twig", [
                "blocks" => $blocks,
                "page" => $page
            ]);
        } else {
            return new Response("Your homepage can not be found.");
        }

    }
}