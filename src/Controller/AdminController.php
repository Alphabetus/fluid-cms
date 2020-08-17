<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function hq()
    {
        return $this->render("base_admin.html.twig");
    }

    /**
     * @Route("/admin/settings", name="admin.settings")
     */

    public function showSettings()
    {
        return $this->render("admin/settings.html.twig");
    }
}