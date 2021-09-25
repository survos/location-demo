<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\Location;
use App\Repository\LocationRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Intl\Countries;

class AppFixtures extends Fixture
{
    private ConsoleOutput $output;
    private ObjectManager $manager;
    private LocationRepository $locationRepository;

    private $lvlCache = [];

    public function load(ObjectManager $manager)
    {
        $this->output = new ConsoleOutput();
        $this->manager = $manager;
        $this->locationRepository = $manager->getRepository(Location::class);

        $this->loadCountries();
        $this->loadIso3166();
        $this->loadCities();
    }


    private function loadCountries()
    {
        $lvl = 1;
        $this->lvlCache[$lvl] = [];
        $this->output->writeln("Loading Countries from Symfony Intl component");
        $countries = Countries::getNames();
        foreach ($countries as $alpha2=>$name) {
            $countryCode = $alpha2;
            $location = new Location($countryCode, $name, $lvl);
            $location
                ->setAlpha2($alpha2);
            $this->manager->persist($location);
            $this->lvlCache[$lvl][$location->getCode()] = $location;
        }
        $this->flushLevel($lvl);
    }

    function flushLevel(int $lvl)
    {
        $this->output->writeln(sprintf("Flushing level $lvl"));
        $this->manager->flush(); // set the IDs
//        $count = $this->locationRepository->count(['lvl'=> $lvl]);
        $count = $this->locationRepository->count([]);
        $this->output->writeln(sprintf("After level $lvl Count is: %d", $count));
        assert($count, "no $lvl locations!");

    }

    // l "states/regions/subcountries" (lvl-2), and 15000 largest cities(lvl-3).
    private function loadIso3166()
    {
        $lvl = 2;
        $this->lvlCache[$lvl] = [];

        $countriesByName = [];
        $json = file_get_contents('https://raw.githubusercontent.com/olahol/iso-3166-2.json/master/iso-3166-2.json');
        // $json = file_get_contents('public/iso-3166-2.json');
        $regions = [];
        $regionsByName = [];

        foreach (json_decode($json) as $countryCode => $country) {

            $parent = $this->lvlCache[$lvl-1][$countryCode] ?? false;
            if (!$parent) {
                continue; // missing TP, East Timor.
            }
            assert($parent, "Missing $countryCode, $country->name in " . join(',', array_keys($this->lvlCache[$lvl-1])));

            foreach ($country->divisions as $stateCode => $stateName) {
                $location = (new Location($stateCode, $stateName))
                    ->setLvl($lvl)
                    ->setParent($parent);
                $this->manager->persist($location);
                $this->lvlCache[$lvl][$stateName] = $location;
            }
        }
        $this->flushLevel($lvl);
    }

    public function loadCities()
    {
        $lvl = 3;
//        $this->lvlCache[$lvl] = [];

        // dump($regions['United States']);

        // now that we have the names loaded into the arrays, we can use them for lookups

        // https://datahub.io/core/world-cities or https://simplemaps.com/data/us-cities
//            $fn = 'https://datahub.io/core/world-cities/r/world-cities.json'; //  or https://simplemaps.com/data/us-cities';
        $fn = __DIR__ . '/../../data/world-cities.json';
        assert(file_exists($fn), $fn);
        $json = file_get_contents($fn);
        $data = json_decode($json);

        foreach ($data as $idx => $cityData) {
//            $city = (new City())
//                ->setName($cityData->name)
//                ->setCode($cityData->geonameid)
//                ->setCountry($cityData->country)
//                ->setSubcountry($cityData->subcountry);
//            $this->manager->persist($city);
            // $this->output->writeln(sprintf("%d) Found %s in %s, %s ", $idx, $cityData->name, $cityData->subcountry, $cityData->country));

            // $country = $countriesByName[$data->country];
            if ($parent = $this->lvlCache[$lvl-1][$cityData->subcountry] ?? false) {
                $cityCode = $cityData->geonameid; // unique, could also be based on country / state / cityName
                $cityLoc = (new Location($cityCode, $cityData->name, $lvl))
                    ->setParent($parent)
                ;
                // set by ID?
                $this->manager->persist($cityLoc);
            } else {
                continue;
                $this->flushLevel($lvl);
                dd($cityData);
                assert($parent, $cityData->subcountry . " missing in " . join("\n", array_keys($this->lvlCache[$lvl-1])));
                // we could create a fake subcountry, but really we need to find level 2
//                $this->output->writeln(sprintf("Unable to find subcountry %s %s in country (%s)", $cityData->subcountry, $cityData->geonameid, $cityData->country));
            }
            // $this->manager->flush();
        }
        $this->flushLevel($lvl);
    }

}
