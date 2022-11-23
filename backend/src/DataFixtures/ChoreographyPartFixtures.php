<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\ChoreographyPart;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ChoreographyPartFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $chorePart_reference = [];
        for ($i = 1; $i <= 30; $i++) {
            $chorePart = new ChoreographyPart();
            $chorePart->setName($faker->name);
            $chorePart->setOrderChoreo(1);
            $chorePart->setCreatedAt(new DateTimeImmutable());
            $chorePart->addVideo($this->getReference('video_' . $i));
            $chorePart->setChoreography($this->getReference('chore_' . rand(1, 10)));
            $chorePart_reference['chorePart_' . $i] = $chorePart;

            $manager->persist($chorePart);
        }

        $manager->flush();

        foreach ($chorePart_reference as $key => $item) {
            $this->addReference($key, $item);
        }
    }

    public function getDependencies()
    {
        return array(
            ChoreographyFixtures::class,
            VideoFixtures::class,
        );
    }
}
