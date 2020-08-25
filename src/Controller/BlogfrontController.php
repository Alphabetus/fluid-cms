<?php


namespace App\Controller;

use App\Repository\GlobalSettingRepository;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogfrontController extends AbstractController
{
    /**
     * @var PageRepository
     */
    private $pageRepository;
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var GlobalSettingRepository
     */
    private $globalSettingRepository;

    public function __construct(PageRepository $pageRepository, GlobalSettingRepository $globalSettingRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->globalSettingRepository = $globalSettingRepository;
    }

    /**
     * @param string $slug
     * @return Response
     * @Route("/{slug}", name="blogfront.page", options={"expose"=true})
     */
    public function showPage(string $slug): Response
    {
        $page = $this->pageRepository->findOneBy(["slug" => $slug]);
        if (!$page) {
            return $this->redirectToRoute("blogfront.not_found");
        }
        $em = $this->getDoctrine()->getManager();
        $page->setViews($page->getViews() + 1);
        $em->flush();

        $nav_pages = $this->pageRepository->findBy(["active" => true]);
        $current_title = $this->globalSettingRepository->findOneBy(["name" => "title"]);

        $blocks = $page->getBlocks();
        return $this->render("blogfront/show_page.html.twig", [
            "nav_pages" => $nav_pages,
            "blocks" => $blocks,
            "page" => $page,
            "current_title" => $current_title
        ]);
    }

    /**
     * @return Response
     * @Route("/page/not-found", name="blogfront.not_found")
     */
    public function notFound(): Response
    {
        $nav_pages = $this->pageRepository->findBy(["active" => true]);
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
        $homepage_setting = $this->globalSettingRepository->findOneBy(["name" => "homepage"])->getValue();
        $maintenanceStatus = $this->globalSettingRepository->findOneBy(["name" => "maintenance"])->getValue();
        $pageRepository = $this->pageRepository;
        if ($homepage_setting == "") {
            return new Response("Configure your homepage in the Administration > Global Settings");
        }

        if ($maintenanceStatus == "true"){
            return $this->render("maintanence.html.twig");
        }

        $nav_pages = $pageRepository->findBy(["active" => true]);
        $current_title = $this->globalSettingRepository->findOneBy(["name" => "title"]);

        $page = $pageRepository->findOneBy(["puid" => $homepage_setting]);

        if ($page && $page->getActive()) {
            $page->setViews($page->getViews() + 1);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $blocks = $page->getBlocks();
            return $this->render("blogfront/show_page.html.twig", [
                "current_title" =>  $current_title,
                "nav_pages" => $nav_pages,
                "blocks" => $blocks,
                "page" => $page
            ]);
        } else {
            return new Response("Your homepage can not be found.");
        }

    }
}