<?php


namespace App\Controller;


use App\Entity\Block;
use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class BlockController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/blocks/new", name="blocks.new")
     */
    public function createBlock(Request $request): JsonResponse
    {
        $id = $request->request->get("page_id");
        $type =  $request->request->get("type");
        $page = $this->getDoctrine()->getRepository(Page::class)->find($id);
        $block = new Block();
        $em = $this->getDoctrine()->getManager();
        $block->setPage($page);
        $block->setType($type);
        $block->setMobileBreakpoint("col-12");
        $block->setDesktopBreakpoint("col-md-12");
        $block->setBuid(Uuid::v1());
        $em->persist($block);
        $em->flush();


        return new JsonResponse($block->getBuid(), 200);
    }

    /**
     * @param Request $request
     * @Route("/blocks/delete", name="blocks.delete")
     */
    public function removeBlock(Request $request): JsonResponse
    {
        $buid = $request->request->get("buid");
        $block = $this->getDoctrine()->getRepository(Block::class)->findOneBy(["buid" => $buid]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($block);
        $em->flush();
        return new JsonResponse("ok", 200);
    }

    protected function logger($content)
    {
        $file = "test.php";
        ob_start();
        var_dump($content);
        $testing = ob_get_clean();
        file_put_contents($file, $testing, FILE_APPEND);
    }
}