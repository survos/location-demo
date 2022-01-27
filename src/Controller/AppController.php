<?php

namespace App\Controller;

use Survos\LocationBundle\Entity\Location;
use Survos\LocationBundle\Repository\LocationRepository;
use Survos\LocationBundle\Entity\GeoName;
use Doctrine\ORM\QueryBuilder;
use Survos\BaseBundle\Traits\JsonResponseTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{

    use JsonResponseTrait;

    #[Route(path: '/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    #[Route(path: '/html-tree', name: 'app_html_tree')]
    public function htmlTree(LocationRepository $locationRepository): Response
    {
        $rootNodes = $locationRepository->findBy(['lvl' => 1    ], [], 30);
        return $this->render('app/html_tree.html.twig', [

            'rootNodes' => $rootNodes,
        ]);
    }

    #[Route(path: '/geoname/{id}', name: 'geoname_show')]
    public function geonameShow(Request $request, GeoName $geoName): void
    {
        dd($geoName);
    }




}
