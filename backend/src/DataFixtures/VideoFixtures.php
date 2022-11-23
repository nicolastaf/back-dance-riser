<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Video;
use DateTimeImmutable;
use App\DataFixtures\MoveFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class VideoFixtures extends Fixture
{
    private $videos = [
        // Top-Rock:
        'https://www.youtube.com/embed/8CEMxxAiB4I',
        'https://www.youtube.com/embed/ngkDXyAnnKc',
        'https://www.youtube.com/embed/aLGL1fpMYl0',
        'https://www.youtube.com/embed/Ja6ocOnm5Z4',
        'https://www.youtube.com/embed/kuvEBUP1DMo',
        'https://www.youtube.com/embed/2wS3Co_Lk0w',
        'https://www.youtube.com/embed/RrDYmNZt9z8',
        'https://www.youtube.com/embed/Kl0YUdWZzG4',
        'https://www.youtube.com/embed/JOVkDYDsHTo',
        'https://www.youtube.com/embed/I0peqPubAU8',
        'https://www.youtube.com/embed/roWh3gNp46',
        'https://www.youtube.com/embed/epGl83aUjls',
        // Baby-Freeze:
        'https://www.youtube.com/embed/3AJT0wIcQhs',
        'https://www.youtube.com/embed/akF4BO7eutQ',
        'https://www.youtube.com/embed/YOuDPqSeYv8',
        'https://www.youtube.com/embed/pS1hmsPAbDY',
        // Technique:
        'https://www.youtube.com/embed/jqTh5DBY52k',
        // Power-Moves:
        'https://www.youtube.com/embed/_BdIBrl6UIQ',
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $video_reference = [];
        for ($i = 1; $i <= 300; $i++) {
            $video = new Video();
            $video->setOrderPart(1);
            $video->setLink($this->video());
            $video->setDescription($faker->realText(100));
            $video->setCreatedAt(new DateTimeImmutable());

            $video_reference['video_' . $i] = $video;

            $manager->persist($video);
        }

        $manager->flush();

        foreach ($video_reference as $key => $item) {
            $this->addReference($key, $item);
        }
    }

    public function video(){
        // Index aléatoire du tableau
        $rand = array_rand($this->videos);
        // return un film aléatoire
        return $this->videos[$rand];
    }
}

