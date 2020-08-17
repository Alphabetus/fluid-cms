<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("{_locale}/admin",
     *     name="admin",
     *     defaults={"_locale"="en"})
     */
    public function hq(Request $request)
    {
        return $this->render("base_admin.html.twig");
    }

    /**
     * @Route("{_locale}/settings",
     *     name="admin.settings",
     *     defaults={"_locale"="en"}
     * )
     */
    public function showSettings()
    {
        return $this->render("admin/settings.html.twig");
    }
}