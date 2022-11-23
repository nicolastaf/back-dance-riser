<?php

namespace App\DataFixtures;

use App\Entity\Level;
use DateTimeImmutable;
use App\Service\MySlugger;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LevelFixtures extends Fixture
{
   
    public const LEVEL_LIST = array(
        'Débutant',
        'Intermédiaire',
        'Avancé',
        'Pro',
    );
    
    public function load(ObjectManager $manager): void
    {
        $level_reference = [];
        $i = 1;
        foreach (self::LEVEL_LIST as $levelValue) {
            $level = new Level();
            $level->setName($levelValue);
            $level->setCreatedAt(new DateTimeImmutable());
            $level_reference['level_' . $i] = $level;

            $manager->persist($level);
            $i++;
        }

        $manager->flush();

        foreach ($level_reference as $key => $item) {
            $this->addReference($key, $item);
        }
    }
}
