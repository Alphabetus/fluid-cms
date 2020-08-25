<?php


namespace App\Controller;


use App\Repository\PageRepository;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminController extends AbstractController
{

    /**
     * @var PageRepository|ObjectRepository
     */
    private $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @Route("{_locale}/admin",
     *     name="admin",
     *     defaults={"_locale"="en"},
     *     options={"expose"=true}
     *     )
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function hq(Request $request, TranslatorInterface $translator)
    {
        $locale = $request->getLocale();
        if (!in_array($locale, $this->getValidLocales())){
            $this->addFlash("error", $translator->trans("app.controller.admincontroller.locale_not_found"));
            return $this->redirectToRoute("admin", ["_locale" => "en"]);
        }

        $total_pages = $this->pageRepository->findBy(array('active' => true));
        $total_pages_count = count($total_pages);
        $total_views = $this->countTotalVisits($total_pages);
        $most_viewed_page = $this->pageRepository->findOneBy(["active" => true], ["views" => "DESC"]);

        return $this->render("admin/dashboard.html.twig", [
            "total_pages" => $total_pages_count,
            "total_views" => $total_views,
            "most_viewed_page" => $most_viewed_page
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @Route("/admin", name="admin.redirect", options={"expose"=true})
     */
    public function mismatch(Request $request)
    {
        return $this->redirectToRoute("admin", ["_locale" => "en"]);
    }

    public static function getValidLocales(): array
    {
        return ["en", "de"];
    }

    public function countTotalVisits($collection): int
    {
        $counter = 0;
        foreach ($collection as $page) {
            $counter += $page->getViews();
        }
        return $counter;
    }
}