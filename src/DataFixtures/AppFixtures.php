<?php

namespace App\DataFixtures;

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
        $json = file_get_contents('https://raw.githubusercontent.com/olahol/iso-3166-2.json/master/iso-3166-2.json');
        // $json = file_get_contents('public/iso-3166-2.json');

        foreach (json_decode($json) as $countryCode => $country) {

            // these are the ROOTs in the truee
            $location = new Location($countryCode, $country->name);
            $this->manager->persist($location);

            foreach ($country->divisions as $stateCode => $stateName) {
                $level2Location = (new Location($stateCode, $stateName))
                    ->setParent($location);
                $this->manager->persist($level2Location);
            }
            // $this->output->writeln(sprintf("%s: %d states/regions", $location->getName(), $location->getChildren()->count()));
        }
    }
}
