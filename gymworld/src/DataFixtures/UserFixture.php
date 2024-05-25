<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixture extends Fixture implements FixtureGroupInterface
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
      
        $faker = Factory::create();
        for ($i=0;$i<100;$i++)
        {
            $client = new User();
            $client->setUsername($faker->userName().$i);
            $client->setName($faker->name().$i);
            $client->setEmail($faker->email().$i);
            $client->setPhoneNumber($faker->phoneNumber().$i);
            $client->setPassword($this->hasher->hashPassword($client,$faker->password)
                .$i);
            $manager->persist($client);
        }
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setName('admin');
        $admin->setPassword($this->hasher->hashPassword($admin,'admin'));
        $admin->setVerified(true);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['user'];
    }
}
