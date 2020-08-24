<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminController extends AbstractController
{
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
        return $this->render("base_admin.html.twig");
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
}