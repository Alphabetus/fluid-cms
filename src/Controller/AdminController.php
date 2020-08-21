<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("{_locale}/admin",
     *     name="admin",
     *     defaults={"_locale"="en"},
     *     options={"expose"=true}
     *     )
     */
    public function hq(Request $request)
    {
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
}