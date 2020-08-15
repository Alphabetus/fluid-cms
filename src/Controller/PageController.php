<?php


namespace App\Controller;


use App\Entity\Page;
use App\Form\EditPageFormType;
use App\Form\NewPageFormType;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PageController extends AbstractController
{
    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     * @Route("/admin/pages/new", name="admin.pages.new")
     */
    public function new(Request $request, ValidatorInterface $validator): Response
    {
        $page = new Page();
        $form = $this->createForm(NewPageFormType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $errors = $validator->validate($page);

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
                $this->addFlash('success', "Your page was created successfully");
                return $this->redirectToRoute("admin.pages.list");
            }
        }

        return $this->render("page/new.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @return Response
     * @Route("/admin/pages/list", name="admin.pages.list")
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
     * @return Response
     * @Route("/admin/pages/edit/{puid}/", name="admin.page.edit")
     */
    public function edit($puid): Response
    {
        $page = $this->getDoctrine()->getRepository(Page::class)->findOneByPuid($puid);
        if (!$page) {
            $this->addFlash('error', "Page not found!");
            return $this->redirectToRoute("admin.pages.list");
        }

        $form = $this->createForm(EditPageFormType::class, $page);
        return $this->render("page/edit.html.twig", [
            "form" => $form->createView(),
            "page" => $page
        ]);
    }
}