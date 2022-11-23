<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\Location;
use App\DataFixtures\SchoolFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LocationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $location_reference = [];
        for ($i = 1; $i <= 10; $i++) {
            $location = new Location();
            $location->setAddress($faker->address);
            $location->setPostalCode($faker->postcode);
            $location->setCity($faker->city);
            $location->setCountry($faker->country);
            $location->setCreatedAt(new DateTimeImmutable());
            $location_reference['location_' . $i] = $location;

            $manager->persist($location);
        }

        $manager->flush();

        foreach ($location_reference as $key => $item) {
            $this->addReference($key, $item);
        }
    }
}
