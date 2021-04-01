<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StateFixtures extends Fixture
{
    public const FIRST_STATE = 'FIRST_STATE';

    public function load(ObjectManager $manager)
    {
        $state = new State();
        $state->setLabel('Créée');
        $manager->persist($state);
        $this->setReference('state_1',$state);


        $state = new State();
        $state->setLabel('Ouverte');
        $manager->persist($state);
        $this->setReference('state_2',$state);


        $state = new State();
        $state->setLabel('Cloturee');
        $manager->persist($state);
        $this->setReference('state_3',$state);


        $state = new State();
        $state->setLabel('Activite en cours');
        $manager->persist($state);
        $this->setReference('state_4',$state);


        $state = new State();
        $state->setLabel('Passee');
        $manager->persist($state);
        $this->setReference('state_5',$state);


        $state = new State();
        $state->setLabel('Annulee');
        $manager->persist($state);
        $this->setReference('state_6',$state);


        $manager->flush();
        $this->setReference(self::FIRST_STATE, $state);
    }
}
