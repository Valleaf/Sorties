<?php

namespace App\DataFixtures;

use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PlaceFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i=1;$i<=40;$i++){
            $place = new Place();
            $name = $faker->company.$faker->streetName;
            $address = $faker->streetAddress;
            $long = $faker->longitude;
            $lat = $faker->latitude;
            $cityName = 'City'.mt_rand(0,20);
            $city = $this->getReference('city_'.mt_rand(1,20));

            $place->setName($name)
                ->setAdress($address)
                ->setLatitude($lat)
                ->setLongitude($long)
                ->setCity($city);
            $manager->persist($place);
            $this->setReference('place_'.$i,$place);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [CityFixtures::class];
    }
}
