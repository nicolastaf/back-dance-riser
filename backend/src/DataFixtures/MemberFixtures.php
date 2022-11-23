<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\School;
use DateTimeImmutable;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\SchoolFixtures;
use App\Entity\Member;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MemberFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        // attribution de 2 écoles parmi les 4 membres User, pour que certains n'est pas d'école
        for ($i = 1; $i <= 2; $i++) {
            $member = new Member();
            $member->setComment($faker->realText(500));
            $member->setActivated(true);
            $member->setSchool($this->getReference('school_' . rand(1, 3)));
            $member->setUser($this->getReference('user_' . rand(3, 6)));
            $member->setNewRequest(false);
            $manager->persist($member);
        }
        // attribution d'une école pour le manager
        $managerUser = new Member();
        $managerUser->setComment($faker->realText(500));
        $managerUser->setActivated(true);
        $managerUser->setSchool($this->getReference('school_' . rand(1, 3)));
        $managerUser->setUser($this->getReference('user_2'));
        $managerUser->setNewRequest(false);
        $manager->persist($managerUser);

        // attribution de toutes les école pour l'admin
        for ($i = 1; $i <= 3; $i++) {
            $admin = new Member();
            $admin->setComment($faker->realText(500));
            $admin->setActivated(true);
            $admin->setSchool($this->getReference('school_' . $i));
            $admin->setUser($this->getReference('user_1'));
            $admin->setNewRequest(false);
            $manager->persist($admin);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            SchoolFixtures::class,
        );
    }
}
