<?php

namespace App\DataFixtures;

use App\Entity\Meeting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use phpDocumentor\Reflection\Types\This;
use function Sodium\add;

class MeetingsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');

        for ($i = 1;$i<=30;$i++) {
            $meet = new Meeting();
            $month = mt_rand(1,12);
            $day = mt_rand(1,28);
            $length = mt_rand(60,360);
            $date = new \DateTime('2021-'.$month.'-'.$day);
            $dateLater = new \DateTime('2021-'.$month.'-'.$day);
            $dateLater->modify('-1 day');
            $organizer = $this->getReference('user_'.mt_rand(1,30));
            $campus = $this->getReference('campus_'.mt_rand(1,10));
            $status = $this->getReference('state_'.mt_rand(1,6));
            $place = $this->getReference('place_'.mt_rand(1,40));


            $meet->setName('Sortie ' . 'des ' . $faker->jobTitle . ' ' . $faker->colorName)
                 ->setTimeStarting($date)
                ->setLength($length)
                ->setRegisterUntil($dateLater)
                ->setMaxParticipants(mt_rand(10,30))
                ->setInformation($faker->paragraph(mt_rand(3,20)))
                ->setCampus($campus)
                ->setPlace($place)
                ->setStatus($status)
                ->setOrganisedBy($organizer);

            for($j = 1; $j<=10;$j++){
                $participants = $this->getReference('user_'.mt_rand(1,30));
                $meet->addParticipant($participants);
            }
            $manager->persist($meet);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class,CampusFixtures::class,StateFixtures::class,PlaceFixtures::class];
    }
}
