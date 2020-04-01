<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Intl\Countries;

class AppFixtures extends Fixture
{
    /** @var ConsoleOutput */
    private $output;

    /** @var ObjectManager */
    private $manager;

    public function load(ObjectManager $manager)
    {
        $this->output = new ConsoleOutput();
        $this->manager = $manager;

        $this->loadIso3166();

        $manager->flush();
    }


    private function loadCountries()
    {
        $this->output->writeln("Loading Countries from Symfony Intl component");
        $countries = Countries::getNames();
        foreach ($countries as $alpha2=>$name) {
            $country = new Location();
            $country
                ->setName($name)
                ->setCode($alpha2)
                ->setAlpha2($alpha2);
            $this->manager->persist($country);
        }
        $this->manager->flush();
    }

    private function loadIso3166()
    {
        $countriesByName = [];
        // https://datahub.io/core/world-cities or https://simplemaps.com/data/us-cities
        $json = file_get_contents('https://raw.githubusercontent.com/olahol/iso-3166-2.json/master/iso-3166-2.json');
        // $json = file_get_contents('public/iso-3166-2.json');
        $regions = [];
        $regionsByName = [];

        foreach (json_decode($json) as $countryCode => $country) {

            // these are the ROOTs in the truee
            $location = new Location($countryCode, $country->name);
            $this->manager->persist($location);
            $countriesByName[$country->name] = $location;

            foreach ($country->divisions as $stateCode => $stateName) {
                $region = (new Location($stateCode, $stateName))
                    ->setParent($location);
                $this->manager->persist($region);
                // array_push($regions, $region);
                // $this->manager->persist($level2Location);
                $regions[$country->name][$stateName] = $location;
            }
            // $location->setChildren($regions); // should set parent, persist?
            // $this->output->writeln(sprintf("%s: %d states/regions", $location->getName(), $location->getChildren()->count()));
        }
        $this->manager->flush(); // set the IDs

        dump(array_keys($regions));
       // dump($regions['United States']);

        // now that we have the names loaded into the arrays, we can use them for lookups

        $fn = 'https://datahub.io/core/world-cities/r/world-cities.json'; //  or https://simplemaps.com/data/us-cities';
        // $fn = __DIR__ . '/../../public/world-cities.json'; @todo: check for this (cache)
        $json = file_get_contents($fn);
        $data = json_decode($json);

        foreach ($data as $idx => $cityData) {
            $city = (new City())
                ->setName($cityData->name)
                ->setCode($cityData->geonameid)
                ->setCountry($cityData->country)
                ->setSubcountry($cityData->subcountry);
            $this->manager->persist($city);
            // $this->output->writeln(sprintf("%d) Found %s in %s, %s ", $idx, $cityData->name, $cityData->subcountry, $cityData->country));

            // $country = $countriesByName[$data->country];
            if ($regions[$cityData->country][$cityData->subcountry] ?? false) {
                $region = $regions[$cityData->country][$cityData->subcountry];

                $cityLoc = (new Location())
                    ->setName($cityData->name)
                    ->setLvl(2)
                    ->setCode($cityData->geonameid);
                // set by ID?
                $cityLoc
                    ->setParent($region);
                $this->manager->persist($cityLoc);
            } else {
                $this->output->writeln(sprintf("Unable to find %s (%s)", $cityData->subcountry, $cityData->country));
            }
            // $this->manager->flush();
        }
        $this->manager->flush();
    }

}
