<?php


namespace App\Controller;


use App\Entity\GlobalSetting;
use App\Repository\GlobalSettingRepository;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SettingController extends AbstractController
{
    /**
     * @var GlobalSettingRepository
     */
    private $globalSettingRepository;
    /**
     * @var PageRepository
     */
    private $pageRepository;

    public function __construct(GlobalSettingRepository $globalSettingRepository, PageRepository $pageRepository)
    {
        $this->globalSettingRepository = $globalSettingRepository;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @param Request $request
     * @Route("/admin/settings/populate_defaults", name="admin.settings.populate_default", options={"expose"=true})
     */
    public function populateDefaults(Request $request): Response
    {
        $default_settings = [
            "homepage" => "",
            "maintenance" => "false",
            "title" => ""
        ];

        $existent_settings = $this->globalSettingRepository->findAll();
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
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function showSettings(Request $request, TranslatorInterface $translator)
    {
        $locale = $request->getLocale();
        if (!in_array($locale, AdminController::getValidLocales())){
            $this->addFlash("error", $translator->trans("app.controller.admincontroller.locale_not_found"));
            return $this->redirectToRoute("admin", ["_locale" => "en"]);
        }
        $pages = $this->pageRepository->findAll();
        $current_homepage = $this->globalSettingRepository->findOneBy(["name" => "homepage"])->getValue();
        $current_maintenance = $this->globalSettingRepository->findOneBy(["name" => "maintenance"])->getValue();
        $current_title = $this->globalSettingRepository->findOneBy(["name" => "title"]);

        return $this->render("admin/settings.html.twig", [
            "current_maintenance" => $current_maintenance,
            "current_homepage" => $current_homepage,
            "current_title" => $current_title,
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
        $setting = $this->globalSettingRepository->findOneBy(["name" => "homepage"]);
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
        $setting = $this->globalSettingRepository->findOneBy(["name" => "maintenance"]);
        $value = $request->request->get('maintenance');
        if ($value == 1) { $value = "true"; } else { $value = "false"; }
        $setting->setValue($value);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new JsonResponse("ok", 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/admin/settings/update/title", name="admin.settings.update.title", options={"expose"=true})
     */
    public function updateTitle(Request $request)
    {
        $setting = $this->globalSettingRepository->findOneBy(["name" => "title"]);
        $new_title = $request->request->get('title');
        $setting->setValue($new_title);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new JsonResponse("ok", 200);
    }
}