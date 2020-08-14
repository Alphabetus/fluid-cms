<?php


namespace App\Controller;


use App\Entity\Page;
use App\Form\NewPageFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
                $errorString = (string) $errors;
                $this->addFlash("error", $errorString);
                return $this->redirectToRoute("admin.pages.new");
            } else {
                $page = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($page);
                $em->flush();
                $this->addFlash('success', "Your page was created successfully");
                return $this->redirectToRoute("admin");
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
        return $this->render("page/list.html.twig");
    }
}