<?php


namespace App\Controller;


use App\Entity\GlobalSetting;
use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingController extends AbstractController
{
    /**
     * @param Request $request
     * @Route("/admin/settings/populate_defaults", name="admin.settings.populate_default", options={"expose"=true})
     */
    public function populateDefaults(Request $request): Response
    {
        $default_settings = [
            "homepage" => "",
            "maintenance" => "false"
        ];

        $existent_settings = $this->getDoctrine()->getRepository(GlobalSetting::class)->findAll();
        $em = $this->getDoctrine()->getManager();

        if (count($existent_settings) > 0) {
            return new Response("there are already settings stored. stopping!", 200);
        }

        foreach ($default_settings as $key => $value) {
            $setting = new GlobalSetting();
            $setting->setName($key);
            $setting->setValue($value);
            $em->persist($setting);
            $em->flush();
        }
        return new Response("ok", 200);
    }

    /**
     * @Route("{_locale}/admin/settings",
     *     name="admin.settings",
     *     defaults={"_locale"="en"},
     *     options={"expose"=true}
     * )
     */
    public function showSettings()
    {
        $pages = $this->getDoctrine()->getRepository(Page::class)->findAll();
        $current_homepage = $this->getDoctrine()->getRepository(GlobalSetting::class)->findOneBy(["name" => "homepage"])->getValue();
        $current_maintenance = $this->getDoctrine()->getRepository(GlobalSetting::class)->findOneBy(["name" => "maintenance"])->getValue();
        return $this->render("admin/settings.html.twig", [
            "current_maintenance" => $current_maintenance,
            "current_homepage" => $current_homepage,
            "pages" => $pages
        ]);
    }

    /**
     * @Route("/admin/settings/update/homepage", name="admin.settings.update.homepage", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateHomepage(Request $request): JsonResponse
    {
        $setting = $this->getDoctrine()->getRepository(GlobalSetting::class)->findOneBy(["name" => "homepage"]);
        $puid = $request->request->get("puid");
        $em = $this->getDoctrine()->getManager();
        $setting->setValue($puid);
        $em->flush();

        return new JsonResponse('ok', 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/admin/settings/update/maintenance", name="admin.settings.update.maintenance", options={"expose"=true})
     */
    public function updateMaintenanceMode(Request $request): JsonResponse
    {
        $setting = $this->getDoctrine()->getRepository(GlobalSetting::class)->findOneBy(["name" => "maintenance"]);
        $value = $request->request->get('maintenance');
        if ($value == 1) { $value = "true"; } else { $value = "false"; }
        $setting->setValue($value);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new JsonResponse("ok", 200);
    }
}