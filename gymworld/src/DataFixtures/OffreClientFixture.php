<?php

namespace App\DataFixtures;

use App\Entity\OffreClient;
use App\Entity\Offres;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class OffreClientFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = $manager->getReference(User::class, 101);
        $offre = $manager->getReference(offres::class, 3);
        $oc = new OffreClient();
        $oc->setClient($user);
        $oc->setOffre($offre);
        $oc->setDateFin(new \DateTime('+1 month'));
        $manager->persist($oc);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['offreClient'];
    }
}