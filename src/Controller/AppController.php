<?php

namespace App\Controller;

use App\Entity\Location;
use Bordeux\Bundle\GeoNameBundle\Entity\GeoName;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/geoname/{id}", name="geoname_show")
     */
    public function geonameShow(Request $request, GeoName $geoName) {
        dd($geoName);
    }

        /**
     * @Route("/location-json", name="location_json")
     */
    public function locationJson(Request $request) {

        $repo = $this->getDoctrine()->getRepository(GeoName::class);
        /** @var QueryBuilder $qb */
        $qb = $repo->createQueryBuilder('l');

        $lvl = $request->get('lvl');
        if (0 && is_numeric($lvl)) {
            $qb->andWhere('l.lvl = :lvl')
                ->setParameter('lvl', $lvl);
        }
        if ($q = $request->get('q')) {
            $qb->andWhere('l.name LIKE :q')
                ->setParameter('q', $q . '%');
        }
        if ($parentCode = $request->get('parentCode')) {
            $parent = $repo->findBy(['code' => $parentCode]);
            $qb->andWhere('l.parent = :parent')
                ->setParameter('parent', $parent);
        }

        $locations = $qb->getQuery()->getResult();
        $data = [];
        foreach ($locations as $location) {
            $data[] = [
                'id' => $location->getId(),
                'text' => $location->getName()
            ];
        }

        return new JsonResponse($data);
    }



}
