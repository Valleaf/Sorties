<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CityFixtures extends Fixture
{


    public function load(ObjectManager $manager)
    {
    $faker = Factory::create('fr_FR');
        for($i=1;$i<=20;$i++){
            $city = new City();
            $cpo = (int) $faker->postcode;
            $nom = $faker->city;
            $city->setName($nom);
            $city->setPostcode($cpo);
            $manager->persist($city);
            $this->setReference('city_'.$i,$city);
        }

        $manager->flush();
    }
}
