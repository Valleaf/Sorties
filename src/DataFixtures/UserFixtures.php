<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $passwordEncoder;

    public const FIRST_USER = 'user_admin';

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }



    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');
        $user = new User();
        $user->setUsername('User');
        $user->setFirstName('Valentin');
        $user->setLastName('Trouillet');
        $user->setEmail('Val@gmail.com');
        $user->setPhone('0478651423');
        $user->setIsAdmin(true);
        $user->setIsActive(true);
        $user->setCampus($this->getReference('campus_'.mt_rand(1,10)));
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'tototo'
        ));

        $manager->persist($user);
        $this->setReference('user_admin',$user);

        for ($i = 1; $i <= 30; $i++) {
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPhone($faker->phoneNumber);
            $user->setIsActive(true);
            $user->setIsAdmin(false);
            $user->setCampus($this->getReference('campus_'.mt_rand(1,10)));
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'tototo'
            ));
            $manager->persist($user);
            $this->setReference('user_'.$i,$user);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [CampusFixtures::class];
    }
}
