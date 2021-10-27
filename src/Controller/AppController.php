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

    #[Route(path: '/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    #[Route(path: '/html-tree', name: 'app_html_tree')]
    public function htmlTree(): Response
    {
        $treeRepository = $this->getDoctrine()->getRepository(Location::class);
        $rootNodes = $treeRepository->findBy(['lvl' => 0], [], 30);
        return $this->render('app/html_tree.html.twig', [

            'rootNodes' => $rootNodes,
        ]);
    }

    #[Route(path: '/geoname/{id}', name: 'geoname_show')]
    public function geonameShow(Request $request, GeoName $geoName): void
    {
        dd($geoName);
    }

    #[Route(path: '/location-json.{_format}', name: 'location_json', defaults: ['_format' => 'html'])]
    public function locationJson(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Location::class);
        /** @var QueryBuilder $qb */
        $qb = $repo->createQueryBuilder('l');
        $lvl = $request->get('lvl', null);
        if (is_numeric($lvl)) {
            $qb->andWhere('l.lvl = :lvl')
                ->setParameter('lvl', $lvl);
        }
        // count the slashes to increase the level.  get parent
        if ($q = $request->get('q')) {
            // us/nc us/north carolina
            // us//chicago
            // //chicago


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
                'id' => $location->getCode(),
                'text' => sprintf("%s (%s) / %d #%d",
                    $location->getName(), $location->getParent() !== null ? $location->getParent()->getCode() : '~', $location->getLvl(), $location->getId()
                )
            ];
        }
        return $this->jsonResponse($data, $request);
    }


}
