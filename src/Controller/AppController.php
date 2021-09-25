<?php

namespace App\Controller;

use App\Entity\Location;
use Bordeux\Bundle\GeoNameBundle\Entity\GeoName;
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
    /**
     * @Route("/", name="app_homepage")
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
        $rootNodes = $treeRepository->findBy(['lvl' => 0], [], 30);
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
     * @Route("/location-json.{_format}", name="location_json", defaults={"_format"="html"})
     */
    public function locationJson(Request $request) {

        $repo = $this->getDoctrine()->getRepository(Location::class);
        /** @var QueryBuilder $qb */
        $qb = $repo->createQueryBuilder('l');

        $lvl = $request->get('lvl', null);
        if (false && is_numeric($lvl)) {
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
        /** @var Location $location */
        foreach ($locations as $location) {
            $data[] = [
                'id' => $location->getId(),
                'text' => sprintf("%s (%s) / %d",
                    $location->getName(), $location->getParent() ? $location->getParent()->getCode(): '~', $location->getLvl()
                )
            ];
        }

        return $this->jsonResponse($data, $request);
    }



}
