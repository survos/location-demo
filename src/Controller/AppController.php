<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Survos\BaseBundle\Traits\JsonResponseTrait;
use Survos\LocationBundle\Entity\Location;
use Survos\LocationBundle\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    use JsonResponseTrait;

    public function __construct(
        private LocationRepository $locationRepository,
        private EntityManagerInterface $entityManager)
    {

    }

    #[Route(path: '/', name: 'app_homepage')]
    public function index(): Response
    {
        $counts = [];
        foreach (['all', 'country', 'state', 'city'] as $idx => $name) {

        }
        for ($i=1; $i<=3; $i++) {
            $counts[$i] = $this->locationRepository->count(['lvl' => $i]);
        }
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
            'counts' => $counts,
        ]);
    }

    #[Route(path: '/html-tree', name: 'app_html_tree')]
    public function htmlTree(): Response
    {
        $treeRepository = $this->entityManager->getRepository(Location::class);
        $rootNodes = $treeRepository->findBy(['lvl' => 1], [], 30);
        return $this->render('app/html_tree.html.twig', [
            'rootNodes' => $rootNodes,
        ]);
    }

    #[Route(path: '/location-browse/{lvl}', name: 'bundle_location_browse')]
    public function location_browse(int $lvl): Response
    {
        return $this->render('app/locations.html.twig', [
            'locationClass' => Location::class,
            'lvl' => $lvl,
            'filter' => [
                'lvl' => $lvl
            ]
        ]);
//        $treeRepository = $this->entityManager->getRepository(Location::class);
//        $rootNodes = $treeRepository->findBy(['lvl' => 1], [], 30);
//        return $this->render('app/html_tree.html.twig', [
//            'rootNodes' => $rootNodes,
//        ]);
    }


    #[Route(path: '/geoname/{id}', name: 'geoname_show')]
    public function geonameShow(Request $request, GeoName $geoName): void
    {
        dd($geoName);
    }

    #[Route(path: '/location-json.{_format}', name: 'location_json', defaults: ['_format' => 'html'])]
    public function locationJson(Request $request)
    {
//        $repo = $this->entityManager->getRepository(Location::class);
        $repo = $this->locationRepository;
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
