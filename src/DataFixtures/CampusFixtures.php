<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CampusFixtures extends Fixture
{


    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');

        for ($i=1;$i<=10;$i++){
            $campus = new Campus();
            $name = 'Ecole ' . $faker->lastName .' '. $faker->lastName;
            $campus->setName($name);
            $manager->persist($campus);
            $this->setReference('campus_'.$i,$campus);

        }

        $manager->flush();
    }
}
