<?php

namespace App\DataFixtures;

use App\Entity\Offres;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OffresFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $offre1= new Offres();
        $offre1->setName('12 months ');
        $offre1->setDuration(12);
        $offre1->setPrice(99.9);
        $manager->persist($offre1);
        $this->addReference('offre1', $offre1);
        $offre2 = new Offres();
        $offre2->setName('9 months ');
        $offre2->setDuration(9);
        $offre2->setPrice(79.9);
        $manager->persist($offre2);
        $this->addReference('offre2', $offre2);
        $offre3 = new Offres();
        $offre3->setName('3 months ');
        $offre3->setDuration(3);
        $offre3->setPrice(49.9);
        $manager->persist($offre3);
        $this->addReference('offre3', $offre3);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['offre'];
    }
}
