<?php


namespace App\Controller;


use App\Entity\Log;
use App\Entity\Page;
use App\Form\EditPageFormType;
use App\Form\NewPageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


class PageController extends AbstractController
{
    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param TranslatorInterface $translator
     * @return Response
     * @Route("{_locale}/admin/pages/new",
     *     name="admin.pages.new",
     *     defaults={"_locale"="en"}
     * )
     */
    public function new(Request $request, ValidatorInterface $validator,TranslatorInterface $translator): Response
    {
        $page = new Page();
        $form = $this->createForm(NewPageFormType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $errors = $validator->validate($page);

            if ($form->getData()->getSlug() == "admin") {
                $this->addFlash("error", $translator->trans("app.controller.pagecontroller.invalid_slug"));
                return $this->redirectToRoute("admin.pages.new");
            }

            if (count($errors) > 0) {
                // we have errors
                $errorString = (string) $errors;
                $this->addFlash("error", $errorString);
                return $this->redirectToRoute("admin.pages.new");
            } else {
                // we do not have errors
                $page = $form->getData();
                $page->setPuid(Uuid::v1());
                $page->setSlug($page::cleanSlug($page->getSlug()));
                $em = $this->getDoctrine()->getManager();
                $em->persist($page);
                $em->flush();
                Log::logEntry("Page",$page->getId(),$page->getSlug(),"created",$em);

                $this->addFlash('success', $translator->trans('app.controller.pagecontroller.createpage_success'));
                return $this->redirectToRoute("admin.pages.list");
            }
        }

        return $this->render("page/new.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @return Response
     * @Route("{_locale}/admin/pages/list",
     *     name="admin.pages.list",
     *     defaults={"_locale"="en"}
     * )
     */
    public function list(): Response
    {
        $pages = $this->getDoctrine()->getRepository(Page::class)->findAll();
        return $this->render("page/list.html.twig", [
            "pages" => $pages
        ]);
    }

    /**
     * @param $puid
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param TranslatorInterface $translator
     * @return Response
     * @Route("{_locale}/admin/pages/edit/{puid}/",
     *     name="admin.page.edit",
     *     defaults={"_locale"="en"}
     * )
     */
    public function edit($puid, Request $request, ValidatorInterface $validator, TranslatorInterface $translator): Response
    {
        $page = $this->getDoctrine()->getRepository(Page::class)->findOneByPuid($puid);
        if (!$page) {
            $this->addFlash('error', $translator->trans('app.controller.pagecontroller.edit_error'));
            return $this->redirectToRoute("admin.pages.list");
        }

        $form = $this->createForm(EditPageFormType::class, $page);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $page = $form->getData();
            $errors = $validator->validate($page);
            if (count($errors) > 0) {
                $error_string = (string) $errors;
                $this->addFlash("error", $error_string);
                return $this->redirectToRoute('admin.page.edit', ['puid' => $page->getPuid()]);
            } else {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                Log::logEntry("Page",$page->getId(),$page->getSlug(),"edited",$em);
                $this->addFlash('success', $translator->trans('app.controller.pagecontroller.edit_success'));
                return $this->redirectToRoute('admin.page.edit', ['puid' => $page->getPuid()]);
            }
        }

        return $this->render("page/edit.html.twig", [
            "form" => $form->createView(),
            "page" => $page
        ]);
    }
}