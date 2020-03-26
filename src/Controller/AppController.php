<?php

namespace App\Controller;

use App\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/app", name="app")
     */
    public function index()
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    /**
     * @Route("/html-tree", name="app_html_tree")
     */
    public function htmlTree()
    {
        $treeRepository = $this->getDoctrine()->getRepository(Location::class);
        $rootNodes = $treeRepository->findBy(['lvl' => 0]);
        return $this->render('app/html_tree.html.twig', [

            'rootNodes' => $rootNodes,
        ]);
    }

}
